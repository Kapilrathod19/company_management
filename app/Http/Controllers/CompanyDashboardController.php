<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyDashboardController extends Controller
{
    public function index()
    {
        return view('company.dashboard');
    }

    public function profile()
    {
        $user = Auth::user();
        $company = Company::where('user_id', $user->id)->first();

        $countries = Country::all();
        $states = State::where('country_id', $company->country_id ?? $countries->first()->id)->get();
        $cities = City::where('state_id', $company->state_id ?? $states->first()->id)->get();

        return view('company.company_profile', compact('user', 'company', 'countries', 'states', 'cities'));
    }

    public function profile_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string',
            'alternate_address' => 'nullable|string',
            'country' => 'required|integer',
            'state' => 'required|integer',
            'city' => 'required|integer',
            'pincode' => 'required|string|max:10',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $company = Company::where('user_id', $user->id)->first();

        // Update User table
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $imageName = $company->image ?? null;
        if ($request->hasFile('image')) {
            if ($company->image && file_exists(public_path('company_images/' . $company->image))) {
                unlink(public_path('company_images/' . $company->image));
            }
            $file = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('company_images'), $imageName);
        }

        $company->update([
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'alternate_address' => $request->alternate_address,
            'country_id' => $request->country,
            'state_id' => $request->state,
            'city_id' => $request->city,
            'pincode' => $request->pincode,
            'image' => $imageName,
        ]);

        return redirect()->route('company.profile')->with('success', 'Profile updated successfully.');
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'ipass' => $request->new_password,
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Password changed successfully. Please log in again.');
    }
}
