<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\Party;
use App\Models\SalesOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class GrnController extends Controller
{
    public function index()
    {
        $grns = Grn::where('user_id', auth()->id())->latest()->get();
        return view('user.grn.list_grn', compact('grns'));
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
        $grn = Grn::where('user_id', auth()->id())->findOrFail($id);

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

            $partyIds = SalesOrder::where('user_id', $userId)->pluck('customer_name')->unique()->toArray();

            $parties = Party::whereIn('id', $partyIds)->get();

            return response()->json(['status' => true, 'data' => $parties]);
        }

        if ($category == "Supplier") {

            $supplierParties = Party::where('user_id', $userId)->where('category', 'Supplier')->pluck('id')->toArray();

            $supplierTablePartyIds = Supplier::where('user_id', $userId)->pluck('supplier_name')->unique()->toArray();

            $finalIds = array_intersect($supplierParties, $supplierTablePartyIds);

            $parties = Party::whereIn('id', $finalIds)->get();

            return response()->json(['status' => true, 'data' => $parties]);
        }

        if ($category == "Jobwork") {

            $jobworkParties = Party::where('user_id', $userId)->where('category', 'Jobwork')->pluck('id')->toArray();

            $supplierTablePartyIds = Supplier::where('user_id', $userId)->pluck('supplier_name')->unique()->toArray();

            $finalIds = array_intersect($jobworkParties, $supplierTablePartyIds);

            $parties = Party::whereIn('id', $finalIds)->get();

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

    public function getPoDetails(Request $request)
    {
        $category = $request->category;
        $partyId  = $request->party_id;
        $poNo     = $request->po_no;
        $userId   = auth()->id();

        if ($category == "Customer") {

            $order = SalesOrder::where('user_id', $userId)->where('customer_name', $partyId)->where('po_no', $poNo)->first();

            if (!$order) {
                return response()->json(['status' => false, 'message' => 'PO Not Found']);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'unit_no'      => $order->unit_no,
                    'part_no'      => $order->item->part_number ?? '',
                    'description'  => $order->description,
                    'qty'          => $order->qty,
                    'weight'       => $order->weight,
                    'total_weight' => $order->total_weight,
                ]
            ]);
        }

        if ($category == "Supplier" || $category == "Jobwork") {

            $supplier = Supplier::with('sales_unit_number', 'item')->where('user_id', $userId)->where('supplier_name', $partyId)->where('po_no', $poNo)->first();

            if (!$supplier) {
                return response()->json(['status' => false, 'message' => 'PO Not Found']);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'unit_no'      => $supplier->sales_unit_number->unit_no ?? '',
                    'part_no'      => $supplier->item->part_number ?? '',
                    'description'  => $supplier->description,
                    'qty'          => $supplier->qty,
                    'weight'       => $supplier->weight,
                    'total_weight' => $supplier->total_weight,
                ]
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Invalid Category']);
    }
}
