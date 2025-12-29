<?php

namespace App\Http\Controllers;

use App\Models\CompanyUser;
use App\Models\Grn;
use App\Models\Item;
use App\Models\Production;
use App\Models\SalesOrder;
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

    public function getUnitNumbers($itemId)
    {
        $units = SalesOrder::where('part_no', $itemId)->pluck('unit_no');

        return response()->json($units);
    }

    public function getSalesOrderByUnit(Request $request)
    {
        $request->validate([
            'unit_no' => 'required|string'
        ]);

        $unitNo = trim($request->unit_no);

        $salesOrders = SalesOrder::where('unit_no', $unitNo)
            ->select('id', 'unit_no', 'po_no', 'po_date')
            ->get();

        $grn = Grn::where('unit_no', $unitNo)
            ->select('party_challan_no', 'party_challan_date')
            ->first();

        $production = null;

        if ($salesOrders->isNotEmpty()) {
            $salesOrderIds = $salesOrders->pluck('id');

            $production = Production::with('processDetails')
                ->whereIn('unit_no', $salesOrderIds)
                ->latest('id')
                ->first();
        }

        return response()->json([
            'sales_orders' => $salesOrders->map(function ($order) {
                return [
                    'unit_no' => $order->unit_no,
                    'po_no'   => $order->po_no,
                    'po_date' => $order->po_date
                        ? Carbon::parse($order->po_date)->format('d-m-Y')
                        : null,
                ];
            }),
            'grn' => $grn ? [
                'party_challan_no'   => $grn->party_challan_no,
                'party_challan_date' => $grn->party_challan_date
                    ? Carbon::parse($grn->party_challan_date)->format('d-m-Y')
                    : null,
            ] : null,
            'production' => $production ? [
                'process' =>
                optional($production->processDetails)->process_number
                    . ' - ' .
                    optional($production->processDetails)->process_name,
                'date' => $production->date
                    ? Carbon::parse($production->date)->format('d-m-Y')
                    : null,
            ] : null
        ]);
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
