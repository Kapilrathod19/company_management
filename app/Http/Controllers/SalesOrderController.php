<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Party;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesorders = SalesOrder::with('party', 'item')->where('user_id', auth()->id())->latest()->get();
        return view('user.sales_orders.list_salesorders', compact('salesorders'));
    }

    public function create()
    {
        $party_names = Party::where('user_id', auth()->id())->where('category', 'Customer')->get();
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
            'delivery_date'     => 'required|date',
        ]);


        $drawing_attachment = null;
        if ($request->hasFile('drawing_attachment')) {
            $file = $request->file('drawing_attachment');
            $drawing_attachment = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('drawing_attachment'), $drawing_attachment);
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

        $salesOrder->delivery_date    = $request->delivery_date;
        $salesOrder->drawing_attachment = $drawing_attachment;
        $salesOrder->save();

        return redirect()->route('sales_order.index')->with('success', 'Sales Order added successfully!');
    }

    public function edit($id)
    {
        $salesOrder = SalesOrder::where('user_id', auth()->id())->findOrFail($id);

        $party_names = Party::where('user_id', auth()->id())
            ->where('category', 'Customer')
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
            'customer_name'     => 'required|exists:parties,id',
            'po_no'             => 'required|string|max:255',
            'po_date'           => 'required|date',
            'part_no'           => 'required|exists:items,id',
            'description'       => 'required|string',
            'unit'              => 'required|string|max:50',
            'qty'               => 'required|numeric',
            'weight'            => 'required|numeric|min:0',
            'total_weight'      => 'nullable|numeric|min:0',
            'delivery_date'     => 'required|date',
        ]);

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
        }

        $salesOrder->customer_name   = $request->customer_name;
        $salesOrder->po_no           = $request->po_no;
        $salesOrder->po_date         = $request->po_date;
        $salesOrder->part_no         = $request->part_no;
        $salesOrder->description     = $request->description;
        $salesOrder->unit            = $request->unit;
        $salesOrder->qty             = $request->qty;
        $salesOrder->weight          = $request->weight;
        $salesOrder->total_weight = number_format(($request->qty * $request->weight), 2, '.', '');
        $salesOrder->delivery_date   = $request->delivery_date;

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
}
