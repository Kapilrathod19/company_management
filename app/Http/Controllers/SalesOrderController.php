<?php

namespace App\Http\Controllers;

use App\Models\SalesOrderDocument;
use App\Models\Employee;
use App\Models\Item;
use App\Models\Party;
use App\Models\Permission;
use App\Models\Process;
use App\Models\ProcessAssignment;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesorders = SalesOrder::with('party', 'item')->where('user_id', auth()->id())->latest()->get();
        $permissions = Permission::where('user_id', auth()->id())->get()->keyBy('module');
        $employees = Employee::where('user_id', auth()->id())->where('status', 'Active')->latest()->get();
        return view('user.sales_orders.list_salesorders', compact('salesorders', 'permissions', 'employees'));
    }

    public function create()
    {
        $party_names = Party::where('user_id', auth()->id())->where('category', 'Customer')->where('status', 1)->get();
        $items = Item::where('user_id', auth()->id())->has('processes')->get();
        return view('user.sales_orders.add_salesorders', compact('party_names', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'     => 'required|exists:parties,id',
            'po_no'             => 'required|string|max:255',
            'po_date'           => 'required|date',
            'part_no'           => 'required|exists:items,id',
            'description'       => 'required|string',
            'unit'              => 'required|string|max:50',
            'qty'               => 'required|numeric',
            'weight'            => 'required|numeric|min:0',
            'total_weight'      => 'nullable|numeric|min:0',
            'unit_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sales_orders')
                    ->where(function ($query) use ($request) {
                        return $query->where('part_no', $request->part_no)
                            ->where('user_id', auth()->id());
                    }),
            ],

            'delivery_date'      => 'required|date',
            'drawing_attachment' => 'required|file',
        ], [
            'unit_no.unique' => 'This Unit No already exists for the selected Component Number.',
        ]);


        $drawing_attachment = null;
        $rev_no = 0;

        if ($request->hasFile('drawing_attachment')) {

            $file = $request->file('drawing_attachment');
            $drawing_attachment = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('drawing_attachment'), $drawing_attachment);

            $rev_no = 0;
        }

        $salesOrder = new SalesOrder();
        $salesOrder->user_id          = auth()->id();
        $salesOrder->customer_name      = $request->customer_name;
        $salesOrder->po_no            = $request->po_no;
        $salesOrder->po_date          = $request->po_date;
        $salesOrder->part_no          = $request->part_no;
        $salesOrder->description      = $request->description;
        $salesOrder->unit             = $request->unit;
        $salesOrder->qty              = $request->qty;
        $salesOrder->weight           = $request->weight;

        $totalWeight = (float)$request->qty * (float)$request->weight;
        $salesOrder->total_weight = number_format($totalWeight, 2, '.', '');

        $salesOrder->remain_qty       = $request->qty;
        $salesOrder->unit_no          = $request->unit_no;
        $salesOrder->rev_no           = $rev_no;
        $salesOrder->delivery_date    = $request->delivery_date;
        $salesOrder->drawing_attachment = $drawing_attachment;
        $salesOrder->save();

        return redirect()->route('sales_order.index')->with('success', 'Sales Order added successfully!');
    }

    public function edit($id)
    {
        $salesOrder = SalesOrder::where('user_id', auth()->id())->findOrFail($id);

        $party_names = Party::where('user_id', auth()->id())
            ->where('category', 'Customer')->where('status', 1)
            ->get();

        $items = Item::where('user_id', auth()->id())
            ->has('processes')
            ->get();

        return view('user.sales_orders.edit_salesorders', compact('salesOrder', 'party_names', 'items'));
    }

    public function update(Request $request, $id)
    {
        $salesOrder = SalesOrder::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'customer_name' => 'required|exists:parties,id',
            'po_no'         => 'required|string|max:255',
            'po_date'       => 'required|date',
            'part_no'       => 'required|exists:items,id',
            'description'   => 'required|string',
            'unit'          => 'required|string|max:50',
            'qty'           => 'required|numeric|min:1',
            'weight'        => 'required|numeric|min:0',
            'unit_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sales_orders')
                    ->where(function ($query) use ($request) {
                        return $query->where('part_no', $request->part_no)
                            ->where('user_id', auth()->id());
                    })
                    ->ignore($salesOrder->id),
            ],

            'delivery_date' => 'required|date',
        ], [
            'unit_no.unique' => 'This Unit No already exists for the selected Component Number.',
        ]);

        $oldQty = $salesOrder->qty;
        $newQty = $request->qty;
        $qtyDiff = $newQty - $oldQty;

        $newRemainQty = $salesOrder->remain_qty + $qtyDiff;

        if ($newRemainQty < 0) {
            return back()->with('error', 'Remain quantity cannot be negative.')->withInput();
        }

        $rev_no = $salesOrder->rev_no;

        if ($request->hasFile('drawing_attachment')) {

            if (
                $salesOrder->drawing_attachment &&
                file_exists(public_path('drawing_attachment/' . $salesOrder->drawing_attachment))
            ) {
                unlink(public_path('drawing_attachment/' . $salesOrder->drawing_attachment));
            }

            $file = $request->file('drawing_attachment');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('drawing_attachment'), $fileName);

            $salesOrder->drawing_attachment = $fileName;
            $rev_no++;
        }

        $salesOrder->customer_name = $request->customer_name;
        $salesOrder->po_no         = $request->po_no;
        $salesOrder->po_date       = $request->po_date;
        $salesOrder->part_no       = $request->part_no;
        $salesOrder->description   = $request->description;
        $salesOrder->unit          = $request->unit;
        $salesOrder->qty           = $newQty;
        $salesOrder->weight        = $request->weight;
        $salesOrder->total_weight  = number_format(($newQty * $request->weight), 2, '.', '');
        $salesOrder->unit_no       = $request->unit_no;
        $salesOrder->remain_qty    = $newRemainQty;
        $salesOrder->rev_no        = $rev_no;
        $salesOrder->delivery_date = $request->delivery_date;

        $salesOrder->save();

        return redirect()->route('sales_order.index')
            ->with('success', 'Sales Order updated successfully!');
    }


    public function destroy($id)
    {
        $SalesOrder = SalesOrder::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        if ($SalesOrder) {
            if (!empty($SalesOrder->drawing_attachment)) {
                $filePath = public_path('drawing_attachment/' . $SalesOrder->drawing_attachment);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $SalesOrder->delete();
            return redirect()->route('sales_order.index')->with('success', 'Sales Order deleted successfully.');
        }

        return redirect()->back()->with('error', 'Sales Order not found.');
    }

    public function getItem($id)
    {
        $item = Item::where('user_id', auth()->id())->find($id);

        if (!$item) {
            return response()->json(['status' => false]);
        }

        return response()->json([
            'status' => true,
            'description' => $item->description,
            'unit' => $item->unit
        ]);
    }

    public function getProcesses($itemId)
    {
        $componentNo = request('component_no');
        $unitNo = request('unit_no');

        $processes = Process::with([
            'processMaster',
            'processAssignment' => function ($q) use ($componentNo, $unitNo) {
                $q->where('user_id', auth()->id())
                ->where('component_no', $componentNo)
                ->where('unit_no', $unitNo);
            }
        ])
        ->where('item_id', $itemId)
        ->orderBy('position')
        ->get();
        return response()->json($processes);
    }

    public function assign(Request $request)
    {
        $request->validate([
            'component_no' => 'required',
            'unit_no' => 'required',
            'processes' => 'required|array',
        ]);

        foreach ($request->processes as $processMasterId => $row) {

            if (!empty($row['employee_id']) && !empty($row['date'])) {

                ProcessAssignment::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'component_no' => $request->component_no,
                        'unit_no' => $request->unit_no,
                        'process_master_id' => $processMasterId,
                    ],
                    [
                        'employee_id' => $row['employee_id'],
                        'process_date' => $row['date'],
                    ]
                );
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Process assigned successfully'
        ]);
    }

    public function uploadDocument(Request $request, $id)
    {
        $salesOrder = SalesOrder::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'document' => 'required|file|max:10240', // 10MB max per file
            'title' => 'required|string|max:255',
        ]);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('sales_order_documents'), $fileName);

            SalesOrderDocument::create([
                'user_id' => auth()->id(),
                'sales_order_id' => $salesOrder->id,
                'title' => $request->input('title'),
                'document' => $fileName,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Document uploaded successfully'
        ]);
    }

    public function getDocuments($id)
    {
        $salesOrder = SalesOrder::where('user_id', auth()->id())->findOrFail($id);
        
        $documents = SalesOrderDocument::where('sales_order_id', $salesOrder->id)
            ->where('user_id', auth()->id())
            ->get();

        return response()->json([
            'status' => true,
            'documents' => $documents
        ]);
    }

    public function deleteDocument($id)
    {
        $document = SalesOrderDocument::where('user_id', auth()->id())->findOrFail($id);

        if (file_exists(public_path('sales_order_documents/' . $document->document))) {
            unlink(public_path('sales_order_documents/' . $document->document));
        }

        $document->delete();

        return response()->json([
            'status' => true,
            'message' => 'Document deleted successfully'
        ]);
    }
}
