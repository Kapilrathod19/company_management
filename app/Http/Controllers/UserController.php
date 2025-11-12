<?php

namespace App\Http\Controllers;

use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
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
