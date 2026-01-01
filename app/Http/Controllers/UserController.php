<?php

namespace App\Http\Controllers;

use App\Models\CompanyUser;
use App\Models\Grn;
use App\Models\Item;
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
        $items = Item::where('category', 'Finished Item')->where('user_id', auth()->id())->get();
        return view('user.dashboard', compact('items'));
    }

    public function getSalesOrdersByItem($itemId)
    {
        $salesOrders = SalesOrder::with('party')
            ->where('part_no', $itemId)
            ->select('id','customer_name', 'unit_no', 'po_no', 'po_date')
            ->get();

        $supplier = Supplier::where('part_no', $itemId)->latest('id')->first();

        $supplierPoDate = $supplier && $supplier->po_date
            ? Carbon::parse($supplier->po_date)->format('d-m-Y')
            : '-';
        
        $supplierPoNo = $supplier && $supplier->po_no
            ? $supplier->po_no
            : '-';

        $rows = [];

        foreach ($salesOrders as $order) {

            $grn = Grn::where('unit_no', $order->unit_no)->first();

            $lastProduction = Production::with('processDetails')
                ->where('unit_no', $order->id)
                ->latest('id')
                ->first();

            $rows[] = [
                'sales_order_id' => $order->id,
                'customer_name' => $order->party->name ?? '-',
                'unit_no' => $order->unit_no,
                'po_no' => $order->po_no,
                'po_date' => $order->po_date
                    ? Carbon::parse($order->po_date)->format('d-m-Y')
                    : '-',
                'supplier_po_no' => $supplierPoNo,
                'supplier_po_date' => $supplierPoDate,
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
                'has_process' => $lastProduction ? true : false
            ];
        }

        return response()->json($rows);
    }

    public function getAllProcessesBySalesOrder($salesOrderId)
    {
        $productions = Production::with('employee', 'processDetails')
            ->where('unit_no', $salesOrderId)
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
