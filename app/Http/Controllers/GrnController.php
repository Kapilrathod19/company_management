<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\Item;
use App\Models\Party;
use App\Models\Permission;
use App\Models\SalesOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class GrnController extends Controller
{
    public function index()
    {
        $grns = Grn::where('user_id', auth()->id())->latest()->get();
        $permissions = Permission::where('user_id', auth()->id())->get()->keyBy('module');
        return view('user.grn.list_grn', compact('grns', 'permissions'));
    }

    public function create()
    {
        return view('user.grn.add_grn');
    }

    public function store(Request $request)
    {
        $request->validate([
            'grn_date'            => 'required|date',
            'category'            => 'required',
            'party_name'          => 'required|exists:parties,id',
            'po_no'               => 'required',
            'party_challan_no'    => 'required',
            'party_challan_date'  => 'required|date',
            'unit_no'             => 'nullable',
            'part_no'             => 'required',
            'description'         => 'required',
            'qty'                 => 'required|numeric',
            'weight'              => 'required|numeric',
            'total_weight'        => 'required|numeric',
            'remark'              => 'nullable|string',
        ]);

        try {

            $lastGrn = GRN::where('user_id', auth()->id())->orderBy('grn_no', 'DESC')->first();

            $nextGrnNo = $lastGrn ? $lastGrn->grn_no + 1 : 1;

            $grn = new GRN();
            $grn->user_id            = auth()->id();
            $grn->grn_no             = $nextGrnNo;
            $grn->grn_date           = $request->grn_date;
            $grn->category           = $request->category;
            $grn->party_name         = $request->party_name;
            $grn->po_no              = $request->po_no;
            $grn->party_challan_no   = $request->party_challan_no;
            $grn->party_challan_date = $request->party_challan_date;
            $grn->unit_no            = $request->unit_no;
            $grn->part_no            = $request->part_no;
            $grn->description        = $request->description;
            $grn->qty                = $request->qty;
            $grn->weight             = $request->weight;
            $grn->total_weight       = $request->total_weight;
            $grn->remark             = $request->remark;
            $grn->save();

            $item = Item::where('user_id', auth()->id())
                ->where('part_number', $request->part_no)
                ->first();

            if ($item) {
                $item->quantity += $request->qty;
                $item->weight   += $request->weight;
                $item->save();
            }

            if ($request->category == "Customer") {

                $salesOrder = SalesOrder::where('user_id', auth()->id())
                    ->where('customer_name', $request->party_name)
                    ->where('po_no', $request->po_no)
                    ->first();

                if ($salesOrder) {

                    $salesOrder->remain_qty = $salesOrder->remain_qty - $request->qty;
                    $salesOrder->save();
                }
            }

            if ($request->category == "Supplier" || $request->category == "Jobwork") {

                $Supplier = Supplier::where('user_id', auth()->id())
                    ->where('supplier_name', $request->party_name)
                    ->where('po_no', $request->po_no)
                    ->first();

                if ($Supplier) {

                    $Supplier->remain_qty = $Supplier->remain_qty - $request->qty;

                    $Supplier->save();
                }
            }


            return redirect()->route('grn.index')->with('success', 'GRN added successfully.');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function edit($id)
    {
        $grn = Grn::where('user_id', auth()->id())->findOrFail($id);
        return view('user.grn.edit_grn', compact('grn'));
    }

    public function update(Request $request, $id)
    {
        $grn = GRN::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'grn_date'            => 'required|date',
            'category'            => 'required',
            'party_name'          => 'required|exists:parties,id',
            'po_no'               => 'required',
            'party_challan_no'    => 'required',
            'party_challan_date'  => 'required|date',
            'unit_no'             => 'nullable',
            'part_no'             => 'required',
            'description'         => 'required',
            'qty'                 => 'required|numeric',
            'weight'              => 'required|numeric',
            'total_weight'        => 'required|numeric',
            'remark'              => 'nullable|string',
        ]);

        try {

            $oldQty    = $grn->qty;

            $newQty    = $request->qty;

            $qtyDiff    = $newQty - $oldQty;

            $item = Item::where('user_id', auth()->id())
                ->where('part_number', $grn->part_no)
                ->first();

            if ($item) {
                $item->quantity += $qtyDiff;
                $item->save();
            }

            if ($grn->category == "Customer") {

                $salesOrder = SalesOrder::where('user_id', auth()->id())
                    ->where('customer_name', $grn->party_name)
                    ->where('po_no', $grn->po_no)
                    ->first();

                if ($salesOrder) {

                    $salesOrder->remain_qty    -= $qtyDiff;
                    $salesOrder->save();
                }
            }

            if ($grn->category == "Supplier" || $grn->category == "Jobwork") {

                $supplier = Supplier::where('user_id', auth()->id())
                    ->where('supplier_name', $grn->party_name)
                    ->where('po_no', $grn->po_no)
                    ->first();

                if ($supplier) {

                    $supplier->remain_qty    -= $qtyDiff;
                    $supplier->save();
                }
            }

            $grn->grn_date           = $request->grn_date;
            $grn->category           = $request->category;
            $grn->party_name         = $request->party_name;
            $grn->po_no              = $request->po_no;
            $grn->party_challan_no   = $request->party_challan_no;
            $grn->party_challan_date = $request->party_challan_date;
            $grn->unit_no            = $request->unit_no;
            $grn->part_no            = $request->part_no;
            $grn->description        = $request->description;
            $grn->qty                = $newQty;
            $grn->weight             = $request->weight;
            $grn->total_weight       = $request->total_weight;
            $grn->remark             = $request->remark;

            $grn->save();

            return redirect()->route('grn.index')->with('success', 'GRN updated successfully.');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function destroy($id)
    {
        try {
            $grn = Grn::where('user_id', auth()->id())->findOrFail($id);
            $grn->delete();

            return redirect()->route('grn.index')->with('success', 'GRN deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function getParties(Request $request)
    {
        $category = $request->category;
        $userId   = auth()->id();

        if ($category == "Customer") {

            $partyIds = SalesOrder::where('user_id', $userId)->where('remain_qty', '>', 0)->pluck('customer_name')->unique()->toArray();

            $parties = Party::whereIn('id', $partyIds)->where('status',1)->get();

            return response()->json(['status' => true, 'data' => $parties]);
        }

        if ($category == "Supplier") {

            $supplierParties = Party::where('user_id', $userId)->where('category', 'Supplier')->where('status',1)->pluck('id')->toArray();

            $supplierTablePartyIds = Supplier::where('user_id', $userId)->where('remain_qty', '>', 0)->pluck('supplier_name')->unique()->toArray();

            $finalIds = array_intersect($supplierParties, $supplierTablePartyIds);

            $parties = Party::whereIn('id', $finalIds)->where('status',1)->get();

            return response()->json(['status' => true, 'data' => $parties]);
        }

        if ($category == "Jobwork") {

            $jobworkParties = Party::where('user_id', $userId)->where('category', 'Jobwork')->where('status',1)->pluck('id')->toArray();

            $supplierTablePartyIds = Supplier::where('user_id', $userId)->where('remain_qty', '>', 0)->pluck('supplier_name')->unique()->toArray();

            $finalIds = array_intersect($jobworkParties, $supplierTablePartyIds);

            $parties = Party::whereIn('id', $finalIds)->where('status',1)->get();

            return response()->json(['status' => true, 'data' => $parties]);
        }

        return response()->json(['status' => true, 'data' => []]);
    }

    public function getPoNumbers(Request $request)
    {
        $partyId = $request->party_id;
        $category = $request->category;
        $userId   = auth()->id();

        if ($category == "Customer") {
            $poList = SalesOrder::where('user_id', $userId)->where('customer_name', $partyId)->pluck('po_no')->unique()->values();

            return response()->json([
                'status' => true,
                'data' => $poList
            ]);
        }

        if ($category == "Supplier") {
            $poList = Supplier::where('user_id', $userId)->where('supplier_name', $partyId)->pluck('po_no')->unique()->values();

            return response()->json([
                'status' => true,
                'data' => $poList
            ]);
        }

        if ($category == "Jobwork") {
            $poList = Supplier::where('user_id', $userId)->where('supplier_name', $partyId)->pluck('po_no')->unique()->values();

            return response()->json([
                'status' => true,
                'data' => $poList
            ]);
        }

        return response()->json(['status' => false, 'data' => []]);
    }

    public function getPoItems(Request $request)
    {
        $category = $request->category;
        $partyId  = $request->party_id;
        $poNo     = $request->po_no;
        $userId   = auth()->id();

        if ($category == "Customer") {

            $orders = SalesOrder::where('user_id', $userId)
                ->where('customer_name', $partyId)
                ->where('po_no', $poNo)
                ->get();

            return response()->json([
                'status' => true,
                'unit_numbers' => $orders->pluck('unit_no')->unique()->values(),
                'part_numbers' => $orders->pluck('item.part_number')->unique()->values(),
            ]);
        }

        if ($category == "Supplier" || $category == "Jobwork") {

            $suppliers = Supplier::with('sales_unit_number', 'item')
                ->where('user_id', $userId)
                ->where('supplier_name', $partyId)
                ->where('po_no', $poNo)
                ->get();

            return response()->json([
                'status' => true,
                'unit_numbers' => $suppliers->pluck('sales_unit_number.unit_no')->unique()->values(),
                'part_numbers' => $suppliers->pluck('item.part_number')->unique()->values(),
            ]);
        }
    }

    public function getItemByUnit(Request $request)
    {
        $unitNo = $request->unit_no;
        $category = $request->category;
        $partyId = $request->party_id;
        $poNo = $request->po_no;

        if ($category == "Customer") {

            $order = SalesOrder::where('user_id', auth()->id())
                ->where('customer_name', $partyId)
                ->where('po_no', $poNo)
                ->where('unit_no', $unitNo)
                ->first();

            if (!$order) return response()->json(['status' => false]);

            return response()->json([
                'status' => true,
                'data' => [
                    'part_no'      => $order->item->part_number,
                    'description'  => $order->description,
                    'qty'          => $order->remain_qty,
                    'weight'       => $order->weight,
                    'total_weight' => $order->remain_qty * $order->weight,
                ]
            ]);
        }

        if ($category == "Supplier" || $category == "Jobwork") {

            $supplier = Supplier::with('item', 'sales_unit_number')
                ->where('user_id', auth()->id())
                ->where('supplier_name', $partyId)
                ->where('po_no', $poNo)
                ->whereHas('sales_unit_number', function ($q) use ($unitNo) {
                    $q->where('unit_no', $unitNo);
                })
                ->first();

            if (!$supplier) return response()->json(['status' => false]);

            return response()->json([
                'status' => true,
                'data' => [
                    'part_no'      => $supplier->item->part_number,
                    'description'  => $supplier->description,
                    'qty'          => $supplier->remain_qty,
                    'weight'       => $supplier->weight,
                    'total_weight' => $supplier->remain_qty * $supplier->weight,
                ]
            ]);
        }
    }

    public function getItemByPart(Request $request)
    {
        $partNo = $request->part_no;
        $category = $request->category;
        $partyId = $request->party_id;
        $poNo = $request->po_no;

        if ($category == "Customer") {

            $order = SalesOrder::where('user_id', auth()->id())
                ->where('customer_name', $partyId)
                ->where('po_no', $poNo)
                ->whereHas('item', function ($q) use ($partNo) {
                    $q->where('part_number', $partNo);
                })
                ->first();

            if (!$order) return response()->json(['status' => false]);

            return response()->json([
                'status' => true,
                'data' => [
                    'unit_no'      => $order->unit_no,
                    'description'  => $order->description,
                    'qty'          => $order->remain_qty,
                    'weight'       => $order->weight,
                    'total_weight' => $order->remain_qty * $order->weight,
                ]
            ]);
        }

        if ($category == "Supplier" || $category == "Jobwork") {

            $supplier = Supplier::with('item', 'sales_unit_number')
                ->where('user_id', auth()->id())
                ->where('supplier_name', $partyId)
                ->where('po_no', $poNo)
                ->whereHas('item', function ($q) use ($partNo) {
                    $q->where('part_number', $partNo);
                })
                ->first();

            if (!$supplier) return response()->json(['status' => false]);

            return response()->json([
                'status' => true,
                'data' => [
                    'unit_no'      => $supplier->sales_unit_number->unit_no ?? null,
                    'description'  => $supplier->description,
                    'qty'          => $supplier->remain_qty,
                    'weight'       => $supplier->weight,
                    'total_weight' => $supplier->remain_qty * $supplier->weight,
                ]
            ]);
        }
    }
}
