<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;

class PartyController extends Controller
{

    public function index()
    {
        $parties = Party::where('user_id', auth()->id())->latest()->get();
        return view('user.party.list_party', compact('parties'));
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
            'email' => 'required|email|max:255',
            'mobile_number' => 'required|string|max:15',
            'gst_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        Party::create([
            'user_id' => auth()->id(),
            'category' => $request->category,
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'gst_number' => $request->gst_number,
            'address' => $request->address,
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
            'email' => 'required|email|max:255',
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
        ]);

        return redirect()->route('party.index')->with('success', 'Party updated successfully.');
    }


    public function destroy($id)
    {
        $party = Party::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $party->delete();

        return redirect()->route('party.index')->with('success', 'Party deleted successfully.');
    }
}
