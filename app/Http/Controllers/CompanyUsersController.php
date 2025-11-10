<?php

namespace App\Http\Controllers;

use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyUsersController extends Controller
{
    public function users()
    {
        $comanyUsers =  CompanyUser::with('company')->where('company_id', Auth::id())->latest()->get();
        return view('company.users.users_list', compact('comanyUsers'));
    }

    public function create_user()
    {
        return view('company.users.create_user');
    }

    public function store_user(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'mobile' => 'required|string|max:15',
            'department' => 'required|string|max:255',
        ]);

        try {

            CompanyUser::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'mobile' => $validated['mobile'],
                'department' => $validated['department'],
                'company_id' => Auth::id(),
            ]);

            return redirect()->route('company.users')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit_user($id)
    {
        $user = CompanyUser::where('company_id', Auth::id())->findOrFail($id);
        return view('company.users.edit_user', compact('user'));
    }

    public function update_user(Request $request, $id)
    {
        $user = CompanyUser::where('company_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_users,email,' . $user->id,
            'mobile' => 'required|string|max:15',
            'department' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        try {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'department' => $validated['department'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return redirect()->route('company.users')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function destroy_user($id)
    {
        $user = CompanyUser::where('company_id', Auth::id())->findOrFail($id);
        $user->delete();
        return redirect()->route('company.users')->with('success', 'User deleted successfully!');
    }
}
