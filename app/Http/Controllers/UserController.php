<?php

namespace App\Http\Controllers;

use App\Models\CompanyUser;
use App\Models\Grn;
use App\Models\Item;
use App\Models\Party;
use App\Models\Production;
use App\Models\SalesOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $items = Item::where('category', 'Finished Item')
            ->where('user_id', auth()->id())
            ->get();

        $customers = Party::where('user_id', auth()->id())->where('category', 'Customer')
            ->orderBy('name')->where('status', '1')->get();

        $salesOrders = SalesOrder::with(['item.processes'])
            ->where('user_id', auth()->id())
            ->get();

        $totalUnits = $salesOrders->count();
        $completedUnits = 0;
        $partialUnits = 0;
        $pendingUnits = 0;

        foreach ($salesOrders as $order) {
            $totalProcesses = $order->item->processes->count();

            $completedProcesses = Production::where('unit_no', $order->id)
                ->where('user_id', auth()->id())
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

        return view('user.dashboard', compact(
            'items',
            'customers',
            'totalUnits',
            'completedUnits',
            'partialUnits',
            'pendingUnits'
        ));
    }

    public function getSalesOrdersByItem(Request $request, $itemId)
    {
        $query = SalesOrder::with(['party', 'item.processes.processMaster'])
            ->where('user_id', auth()->id())->where('part_no', $itemId);

        if ($request->filled('customer_id')) {
            $query->where('customer_name', $request->customer_id);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('po_date', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        }

        $salesOrders = $query->get();
        $supplier = Supplier::where('part_no', $itemId)->where('user_id', auth()->id())->latest('id')->first();
        $rows = [];

        foreach ($salesOrders as $order) {

            $totalProcesses = $order->item->processes->count();

            $completedProcesses = Production::where('unit_no', $order->id)->where('user_id', auth()->id())
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
                ->where('unit_no', $order->id)->where('user_id', auth()->id())
                ->latest('id')
                ->first();

            $grn = Grn::where('unit_no', $order->unit_no)->where('user_id', auth()->id())->first();

            $rows[] = [

                'sales_order_id' => $order->id,
                'customer_name' => $order->party->name ?? '-',
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

    public function getAllProcessesBySalesOrder($salesOrderId)
    {
        $productions = Production::with('employee', 'processDetails')
            ->where('unit_no', $salesOrderId)->where('user_id', auth()->id())
            ->orderBy('id')
            ->get();

        $data = $productions->map(function ($item) {
            return [
                'employee' => $item->employee ? ($item->employee->emp_no ?? '-') . ' - ' . ($item->employee->employee_name ?? '-') : '-',
                'qty' => $item->qty ?? '-',
                'weight' => $item->weight ?? '-',
                'process_date' => isset($item->date)
                    ? Carbon::parse($item->date)->format('d-m-Y')
                    : '-',
                'process' => $item->processDetails
                    ? $item->processDetails->process_number . ' - ' . $item->processDetails->process_name
                    : '-',
            ];
        });

        return response()->json($data);
    }

    public function profile()
    {
        $user = CompanyUser::findOrFail(Auth::id());
        return view('user.user_profile', compact('user'));
    }

    public function profile_store(Request $request)
    {
        $user = Auth::guard('company_user')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_users,email,' . $user->id,
            'mobile' => 'required|regex:/^[0-9]{10,15}$/',
            'department' => 'nullable|string|max:255',
        ]);

        try {
            $user->update($validated);

            return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function change_password(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required',
            ]);

            $user = CompanyUser::findOrFail(Auth::id());

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->route('user.profile')->with('error', 'Current password is incorrect.');
            }

            if ($request->new_password != $request->confirm_password) {
                return redirect()->route('user.profile')->with('error', 'New password and confirm password do not match.');
            }

            $user->update([
                'password' => Hash::make($request->new_password),
                'ipass' => $request->new_password,
            ]);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Password updated successfully. Please log in again.');
        } catch (\Exception $e) {
            return redirect()->route('user.profile')->with('error', 'Something went wrong. Please try again.');
        }
    }
}
