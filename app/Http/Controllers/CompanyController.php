<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\State;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function company()
    {
        $companies = Company::with('user', 'state', 'city')->latest()->get();
        return view('admin.company.list_company', compact('companies'));
    }

    public function create_company()
    {
        $states = State::all();
        return view('admin.company.add_company', compact('states'));
    }

    public function store_company(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string',
            'alternate_address' => 'nullable|string',
            'state' => 'required|integer',
            'city' => 'required|integer',
            'pincode' => 'required|string|max:10',
            'gst_no' => 'nullable|string|max:50',
            'msme_no' => 'nullable|string|max:50',
            'state_code' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'ipass' => $validated['password'],
                'role' => 'company',
            ]);

            $imageName = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('company_images'), $imageName);
            }

            Company::create([
                'user_id' => $user->id,
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'alternate_address' => $validated['alternate_address'] ?? null,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'pincode' => $validated['pincode'],
                'gst_no' => $validated['gst_no'] ?? null,
                'msme_no' => $validated['msme_no'] ?? null,
                'state_code' => $validated['state_code'] ?? null,
                'image' => $imageName,
            ]);

            return redirect()->route('admin.company')->with('success', 'Company created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit_company($id)
    {
        $company = Company::find($id);
        $states = State::all();
        return view('admin.company.edit_company', compact('company','states'));
    }

    public function update_company(Request $request, $id)
    {
        $Company = Company::findOrFail($id);
        $user = User::findOrFail($Company->user_id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string',
            'alternate_address' => 'nullable|string',
            'state' => 'required|integer',
            'city' => 'required|integer',
            'pincode' => 'required|string|max:10',
            'gst_no' => 'nullable|string|max:50',
            'msme_no' => 'nullable|string|max:50',
            'state_code' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $request->filled('password')
                    ? Hash::make($validated['password'])
                    : $user->password,
                'ipass' => $request->filled('password') ? $validated['password'] : $user->ipass,
            ]);

            $imageName = $Company->image;
            if ($request->hasFile('image')) {
                if ($Company->image && file_exists(public_path('company_images/' . $Company->image))) {
                    unlink(public_path('company_images/' . $Company->image));
                }
                $file = $request->file('image');
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('company_images'), $imageName);
            }

            $Company->update([
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'alternate_address' => $validated['alternate_address'] ?? null,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'pincode' => $validated['pincode'],
                'gst_no' => $validated['gst_no'] ?? null,
                'msme_no' => $validated['msme_no'] ?? null,
                'state_code' => $validated['state_code'] ?? null,
                'image' => $imageName,
            ]);

            return redirect()->route('admin.company')->with('success', 'Company updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function destroy_company($id)
    {
        $company = Company::find($id);

        if ($company) {
            if (!empty($company->image)) {
                $imagePath = public_path('company_images/' . $company->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            if ($company->user_id) {
                $user = User::find($company->user_id);
                if ($user) {
                    $user->delete();
                }
            }
            $company->delete();
            return redirect()->route('admin.company')->with('success', 'Company and related user deleted successfully.');
        }

        return redirect()->back()->with('error', 'Company not found.');
    }
}
