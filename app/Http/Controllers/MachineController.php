<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::where('user_id', auth()->id())->latest()->get();
        return view('user.machine.list_machine', compact('machines'));
    }

    public function create()
    {
        return view('user.machine.add_machine');
    }


    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'machine_no' => 'required|string|max:255',
            'machine_name' => 'required|string|max:100',
            'calibration_date' => 'required|date',
            'remark' => 'nullable|string',
        ]);

        Machine::create([
            'user_id' => auth()->id(),
            'category' => $request->category,
            'machine_no' => $request->machine_no,
            'machine_name' => $request->machine_name,
            'calibration_date' => $request->calibration_date,
            'remark' => $request->remark,
        ]);

        return redirect()->route('machine.index')->with('success', 'machine created successfully.');
    }

    public function edit($id)
    {
        $item = Machine::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        return view('user.machine.edit_machine', compact('item'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'machine_no' => 'required|string|max:255',
            'machine_name' => 'required|string|max:100',
            'calibration_date' => 'required|date',
            'remark' => 'nullable|string',
        ]);

        $machine = Machine::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        $machine->update([
            'category' => $request->category,
            'machine_no' => $request->machine_no,
            'machine_name' => $request->machine_name,
            'calibration_date' => $request->calibration_date,
            'remark' => $request->remark,
        ]);

        return redirect()->route('machine.index')->with('success', 'machine updated successfully.');
    }

    public function destroy($id)
    {
        $machine = Machine::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        if ($machine) {
            $machine->delete();
            return redirect()->route('machine.index')->with('success', 'machine deleted successfully.');
        }

        return redirect()->back()->with('error', 'machine not found.');
    }
}
