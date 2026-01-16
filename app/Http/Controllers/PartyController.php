<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PartyController extends Controller
{

    public function index()
    {
        $parties = Party::where('user_id', auth()->id())->latest()->get();
        $permissions = Permission::where('user_id', auth()->id())->get()->keyBy('module');
        return view('user.party.list_party', compact('parties', 'permissions'));
    }


    public function create()
    {
        return view('user.party.add_party');
    }


    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email'         => [
                'required',
                'email',
                'max:255',
                Rule::unique('parties', 'email')
                    ->where('category', $request->category),
            ],
            'mobile_number' => 'required|string|max:15',
            'gst_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        Party::create([
            'user_id' => auth()->id(),
            'category' => $request->category,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_number' => $request->mobile_number,
            'gst_number' => $request->gst_number,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        return redirect()->route('party.index')->with('success', 'Party created successfully.');
    }

    public function edit($id)
    {
        $party = Party::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        return view('user.party.edit_party', compact('party'));
    }


    public function update(Request $request, $id)
    {
        $party = Party::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email'         => [
                'required',
                'email',
                Rule::unique('parties', 'email')
                    ->where('category', $request->category)
                    ->ignore($party->id),
            ],
            'mobile_number' => 'required|string|max:15',
            'gst_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $party->update([
            'category' => $request->category,
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'gst_number' => $request->gst_number,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $party->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('party.index')->with('success', 'Party updated successfully.');
    }

    public function destroy($id)
    {
        $party = Party::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $party->delete();

        return redirect()->route('party.index')->with('success', 'Party deleted successfully.');
    }
}
