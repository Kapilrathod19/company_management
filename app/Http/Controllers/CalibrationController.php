<?php

namespace App\Http\Controllers;

use App\Models\Calibration;
use App\Models\Machine;
use App\Models\Permission;
use Illuminate\Http\Request;

class CalibrationController extends Controller
{
    public function index()
    {
        $calibration = Calibration::with('machine')->where('user_id', auth()->id())->latest()->get();
        $permissions = Permission::where('user_id', auth()->id())->get()->keyBy('module');
        return view('user.calibration.list_calibration', compact('calibration', 'permissions'));
    }

    public function create()
    {
        $machines = Machine::where('category', 'Tools')->where('user_id', auth()->id())->latest()->get();
        return view('user.calibration.add_calibration', compact('machines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'machine_name' => 'required',
            'machine_no' => 'required',
            'calibration_date' => 'required',
            'certificate' => 'nullable',
        ]);

        $certificate = null;
        if ($request->hasFile('certificate')) {
            $file = $request->file('certificate');
            $certificate = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('certificate'), $certificate);
        }

        Calibration::create([
            'user_id' => auth()->id(),
            'date' => $request->date,
            'machine_no' => $request->machine_no,
            'machine_name' => $request->machine_name,
            'calibration_date' => $request->calibration_date,
            'certificate' => $certificate,
        ]);

        Machine::where('id', $request->machine_name)
            ->where('user_id', auth()->id())
            ->update([
                'calibration_date' => $request->calibration_date,
            ]);

        return redirect()->route('calibration.index')->with('success', 'Calibration added successfully.');
    }

    public function edit($id)
    {
        $calibration = Calibration::where('user_id', auth()->id())->findOrFail($id);
        $machines = Machine::where('category', 'Tools')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.calibration.edit_calibration', compact('calibration', 'machines'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'machine_name' => 'required',
            'machine_no' => 'required',
            'calibration_date' => 'required',
            'certificate' => 'nullable',
        ]);

        $calibration = Calibration::where('user_id', auth()->id())->findOrFail($id);
        $certificate = $calibration->certificate;

        if ($request->hasFile('certificate')) {

            if ($certificate && file_exists(public_path('certificate/' . $certificate))) {
                unlink(public_path('certificate/' . $certificate));
            }

            $file = $request->file('certificate');
            $certificate = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('certificate'), $certificate);
        }

        $calibration->update([
            'date' => $request->date,
            'machine_no' => $request->machine_no,
            'machine_name' => $request->machine_name,
            'calibration_date' => $request->calibration_date,
            'certificate' => $certificate,
        ]);

        Machine::where('id', $request->machine_name)
            ->where('user_id', auth()->id())
            ->update([
                'calibration_date' => $request->calibration_date,
            ]);

        return redirect()->route('calibration.index')
            ->with('success', 'Calibration updated successfully.');
    }


    public function getMachineDetails($id)
    {
        $machine = Machine::where('user_id', auth()->id())->find($id);

        if (!$machine) {
            return response()->json(['status' => false]);
        }

        return response()->json([
            'status' => true,
            'machine_no' => $machine->machine_no,
            'calibration_date' => $machine->calibration_date,
        ]);
    }
}
