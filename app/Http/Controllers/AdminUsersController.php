<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{
    public function users()
    {
        $comanyUsers =  CompanyUser::with('company')->latest()->get();
        return view('admin.users.users_list', compact('comanyUsers'));
    }

    public function create_user()
    {
        $companies = Company::latest()->get();
        return view('admin.users.create_user', compact('companies'));
    }

    public function store_user(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_users,email',
            'password' => 'required|string|min:6',
            'mobile' => 'required|string|max:15',
            'department' => 'required|string|max:255',
        ]);

        try {

            CompanyUser::create([
                'company_id' => $validated['company_id'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'mobile' => $validated['mobile'],
                'department' => $validated['department'],
            ]);

            return redirect()->route('admin.users')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit_user($id)
    {
        $user = CompanyUser::findOrFail($id);
        $companies = Company::latest()->get();
        return view('admin.users.edit_user', compact('user', 'companies'));
    }

    public function update_user(Request $request, $id)
    {
        $user = CompanyUser::findOrFail($id);

        $validated = $request->validate([
            'company_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_users,email,' . $user->id,
            'mobile' => 'required|string|max:15',
            'department' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        try {
            $updateData = [
                'company_id' => $validated['company_id'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'department' => $validated['department'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return redirect()->route('admin.users')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function destroy_user($id)
    {
        $user = CompanyUser::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
}
