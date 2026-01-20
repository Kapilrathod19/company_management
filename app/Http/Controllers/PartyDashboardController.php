<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\Item;
use App\Models\Party;
use App\Models\ProcessAssignment;
use App\Models\Production;
use App\Models\SalesOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PartyDashboardController extends Controller
{
    public function index()
    {
        $customers = Party::where('id', auth()->id())->where('category', 'Customer')
            ->orderBy('name')->where('status', '1')->first();

        $salesOrders = SalesOrder::with(['item.processes'])
            ->where('customer_name', $customers->id)
            ->whereHas('item', function ($q) {
                $q->where('category', 'Finished Item');
            })
            ->get();

        // ONLY items which exist in sales orders
        $items = $salesOrders
            ->pluck('item')
            ->unique('id')
            ->values();

        $totalUnits = $salesOrders->count();
        $completedUnits = 0;
        $partialUnits = 0;
        $pendingUnits = 0;

        foreach ($salesOrders as $order) {
            $totalProcesses = $order->item->processes->count();

            $completedProcesses = Production::where('unit_no', $order->id)
                ->where('user_id', $customers->user_id)
                ->distinct('process')
                ->count('process');

            if ($completedProcesses == 0) {
                $pendingUnits++;
            } elseif ($completedProcesses < $totalProcesses) {
                $partialUnits++;
            } else {
                $completedUnits++;
            }
        }

        return view('party.dashboard', compact(
            'items',
            'totalUnits',
            'completedUnits',
            'partialUnits',
            'pendingUnits'
        ));
    }

    public function profile()
    {
        $user = Party::findOrFail(Auth::id());
        return view('party.profile', compact('user'));
    }

    public function profile_store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'mobile_number' => 'required|string|min:10|max:15',
                'gst_number' => 'required',
                'address' => 'required',
            ]);
            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'gst_number' => $request->gst_number,
                'address' => $request->address,
            );
            Party::where('id', $request->id)->update($data);
            return redirect()->route('party.profile')->with('success', 'profile updated successfully');
        } catch (Exception $e) {
            return redirect()->route('party.profile')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function change_password(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required',
            ]);

            $user = Party::findOrFail(Auth::id());

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->route('party.profile')->with('error', 'Current password is incorrect.');
            }

            if ($request->new_password != $request->confirm_password) {
                return redirect()->route('party.profile')->with('error', 'New password and confirm password do not match.');
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Password updated successfully. Please log in again.');
        } catch (\Exception $e) {
            return redirect()->route('party.profile')->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function getSalesOrdersByItem(Request $request, $itemId)
    {
        $customers = Party::where('id', auth()->id())->where('category', 'Customer')
            ->orderBy('name')->where('status', '1')->first();

        $query = SalesOrder::with(['party', 'item.processes.processMaster'])
            ->where('customer_name', $customers->id)->where('part_no', $itemId);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('po_date', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        }

        $salesOrders = $query->get();
        $supplier = Supplier::where('part_no', $itemId)->where('user_id', $customers->user_id)->latest('id')->first();
        $rows = [];

        foreach ($salesOrders as $order) {

            $totalProcesses = $order->item->processes->count();

            $completedProcesses = Production::where('unit_no', $order->id)->where('user_id', $customers->user_id)
                ->where('component_no', $request->partNumber)
                ->distinct('process')
                ->count('process');
            /** STATUS */
            if ($completedProcesses == 0) {
                $status = 'not_started'; // RED
            } elseif ($completedProcesses < $totalProcesses) {
                $status = 'partial'; // YELLOW
            } else {
                $status = 'completed'; // GREEN
            }

            $lastProduction = Production::with('processDetails')
                ->where('unit_no', $order->id)->where('user_id', $customers->user_id)
                ->latest('id')
                ->first();

            $grn = Grn::where('unit_no', $order->unit_no)->where('user_id', $customers->user_id)->first();
            $rows[] = [

                'sales_order_id' => $order->id,
                'customer_name' => $order->party->name ?? '-',
                'part_no' => $itemId,
                'unit_no' => $order->unit_no,
                'po_no' => $order->po_no,
                'po_date' => $order->po_date
                    ? Carbon::parse($order->po_date)->format('d-m-Y')
                    : '-',
                'supplier_po_no' => $supplier->po_no ?? '-',
                'supplier_po_date' => isset($supplier->po_date)
                    ? Carbon::parse($supplier->po_date)->format('d-m-Y')
                    : '-',
                'party_challan_no' => $grn->party_challan_no ?? '-',
                'party_challan_date' => isset($grn->party_challan_date)
                    ? Carbon::parse($grn->party_challan_date)->format('d-m-Y')
                    : '-',
                'production_date' => isset($lastProduction->date)
                    ? Carbon::parse($lastProduction->date)->format('d-m-Y')
                    : '-',
                'process' => $lastProduction && $lastProduction->processDetails
                    ? $lastProduction->processDetails->process_number . ' - ' . $lastProduction->processDetails->process_name
                    : '-',

                'has_process' => $completedProcesses > 0,
                'process_status' => $status,
            ];
        }

        return response()->json($rows);
    }

    public function getAllProcessesBySalesOrder(Request $request, $salesOrderId)
    {
        $componentNo = $request->component_no;
        $unitNo      = $request->unit_no;

        $customers = Party::where('id', auth()->id())->where('category', 'Customer')
            ->orderBy('name')->where('status', '1')->first();

        $productions = Production::with('employee', 'processDetails')
            ->where('unit_no', $salesOrderId)->where('user_id', $customers->user_id)
            ->orderBy('id')
            ->get();


        $data = $productions->map(function ($prod, $index) use ($componentNo, $unitNo) {
            // Assigned process (planned)

            $assignment = ProcessAssignment::with('employee')
                ->where('user_id', $prod->user_id)
                ->where('unit_no', $unitNo)
                ->where('component_no', $componentNo)
                ->where('process_master_id', $prod->process)
                ->first();

            $assignedDate = $assignment?->process_date;
            $processDate  = $prod->date;

            // Date difference
            $difference = ($assignedDate && $processDate)
                ? Carbon::parse($assignedDate)->diffInDays(Carbon::parse($processDate), false)
                : null;

            return [
                'sr_no' => $index + 1,

                'assigned_employee' => $assignment && $assignment->employee
                    ? ($assignment->employee->emp_no ?? '-') . ' - ' . ($assignment->employee->employee_name ?? '-')
                    : '-',

                'actual_employee' => $prod->employee
                    ? ($prod->employee->emp_no ?? '-') . ' - ' . ($prod->employee->employee_name ?? '-')
                    : '-',

                'qty' => $prod->qty ?? '-',
                'weight' => $prod->weight ?? '-',

                'assigned_date' => $assignedDate
                    ? Carbon::parse($assignedDate)->format('d-m-Y')
                    : '-',

                'process_date' => $processDate
                    ? Carbon::parse($processDate)->format('d-m-Y')
                    : '-',

                'process' => $prod->processDetails
                    ? $prod->processDetails->process_number . ' - ' . $prod->processDetails->process_name
                    : '-',

                'difference' => is_numeric($difference)
                    ? $difference . ' Days'
                    : '-',
            ];
        });

        return response()->json($data);
    }
}
