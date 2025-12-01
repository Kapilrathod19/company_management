<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Party;
use App\Models\SalesOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('party', 'item', 'sales_order', 'sales_unit_number')->where('user_id', auth()->id())->latest()->get();
        return view('user.supplier.list_supplier', compact('suppliers'));
    }

    public function create()
    {
        $supplier_name = Party::where('user_id', auth()->id())->whereIn('category', ['Supplier', 'Jobwork'])->get();

        $salesorders = SalesOrder::with('party')
            ->where('user_id', auth()->id())->get()->groupBy('customer_name')->map->first();

        $items = Item::where('user_id', auth()->id())->get();

        return view('user.supplier.add_supplier', compact('supplier_name', 'salesorders', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|exists:parties,id',
            'po_no'         => 'required|string|max:255',
            'po_date'       => 'required|date',
            'customer_name' => 'required|exists:parties,id',
            'part_no'       => 'required|exists:items,id',
            'description'   => 'required|string',
            'qty'           => 'required|numeric|min:1',
            'weight'        => 'required|numeric|min:0',
            'remark'        => 'nullable|string|max:255',
        ]);

        try {

            $totalWeight = (float)$request->qty * (float)$request->weight;
            $totalWeight = number_format($totalWeight, 2, '.', '');

            Supplier::create([
                'user_id'       => auth()->id(),
                'supplier_name' => $request->supplier_name,
                'po_no'         => $request->po_no,
                'po_date'       => $request->po_date,
                'customer_name' => $request->customer_name,
                'sales_po_no'   => $request->sales_po_no,
                'unit_no'       => $request->unit_no,
                'part_no'       => $request->part_no,
                'description'   => $request->description,
                'qty'           => $request->qty,
                'weight'        => $request->weight,
                'remain_qty'    => $request->qty,
                'total_weight'  => $totalWeight,
                'remark'        => $request->remark,
            ]);

            return redirect()
                ->route('supplier.index')
                ->with('success', 'Supplier PO added successfully.');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $supplier = Supplier::where('user_id', auth()->id())->findOrFail($id);

        $supplier_name = Party::where('user_id', auth()->id())->whereIn('category', ['Supplier', 'Jobwork'])->get();

        $salesorders = SalesOrder::with('party')
            ->where('user_id', auth()->id())
            ->get()
            ->groupBy('customer_name')
            ->map->first();

        $items = Item::where('user_id', auth()->id())->get();

        return view('user.supplier.edit_supplier', compact('supplier', 'supplier_name', 'salesorders', 'items'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_name' => 'required|exists:parties,id',
            'po_no'         => 'required|string|max:255',
            'po_date'       => 'required|date',
            'customer_name' => 'required|exists:parties,id',
            'part_no'       => 'required|exists:items,id',
            'description'   => 'required|string',
            'qty'           => 'required|numeric|min:1',
            'weight'        => 'required|numeric|min:0',
            'remark'        => 'nullable|string|max:255',
        ]);

        try {

            $supplier = Supplier::where('user_id', auth()->id())->findOrFail($id);

            $oldQty = $supplier->qty;
            $newQty = $request->qty;

            $qtyDiff = $newQty - $oldQty;

            $newRemainQty = $supplier->remain_qty + $qtyDiff;

            if ($newRemainQty < 0) {
                return back()->with('error', 'Remain quantity cannot be negative.')->withInput();
            }

            $newWeight = $request->weight;
            $totalWeight = number_format($newQty * $newWeight, 2, '.', '');

            $supplier->update([
                'supplier_name' => $request->supplier_name,
                'po_no'         => $request->po_no,
                'po_date'       => $request->po_date,
                'customer_name' => $request->customer_name,
                'sales_po_no'   => $request->sales_po_no,
                'unit_no'       => $request->unit_no,
                'part_no'       => $request->part_no,
                'description'   => $request->description,
                'qty'           => $newQty,
                'weight'        => $newWeight,
                'total_weight'  => $totalWeight,
                'remain_qty'    => $newRemainQty,
                'remark'        => $request->remark,
            ]);

            return redirect()
                ->route('supplier.index')
                ->with('success', 'Supplier PO updated successfully.');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage())
                ->withInput();
        }
    }



    public function destroy($id)
    {
        $Supplier = Supplier::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        if ($Supplier) {
            $Supplier->delete();
            return redirect()->route('supplier.index')->with('success', 'Supplier PO deleted successfully.');
        }

        return redirect()->back()->with('error', 'Supplier PO not found.');
    }

    public function getUnitNo($customer)
    {
        return SalesOrder::where('user_id', auth()->id())
            ->where('customer_name', $customer)
            ->select('id', 'unit_no')
            ->get();
    }

    public function getSalesOrderById($id)
    {
        $salesOrder = SalesOrder::where('user_id', auth()->id())->findOrFail($id);

        return response()->json([
            'sales_po_no'  => $salesOrder->po_no,
            'part_no'      => $salesOrder->part_no,
            'description'  => $salesOrder->description,
            'qty'          => $salesOrder->qty,
            'weight'       => $salesOrder->weight,
            'total_weight' => $salesOrder->total_weight,
        ]);
    }

    public function getItemDetails($id)
    {
        $item = Item::where('user_id', auth()->id())->findOrFail($id);

        return response()->json([
            'description' => $item->description,
            'qty'         => $item->quantity,
            'weight'      => $item->weight,
        ]);
    }
}
