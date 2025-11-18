<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::where('user_id', auth()->id())->latest()->get();
        return view('user.employee.list_employee', compact('employees'));
    }


    public function create()
    {
        return view('user.employee.add_employee');
    }


    public function store(Request $request)
    {
        $request->validate([
            'contractor_name' => 'required|string|max:255',
            'emp_no' => 'required|string|max:100',
            'employee_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'contact_no' => 'required|string|max:15',
        ]);

        $certificate = null;
        if ($request->hasFile('certificate')) {
            $file = $request->file('certificate');
            $certificate = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('certificate'), $certificate);
        }

        Employee::create([
            'user_id' => auth()->id(),
            'contractor_name' => $request->contractor_name,
            'emp_no' => $request->emp_no,
            'employee_name' => $request->employee_name,
            'designation' => $request->designation,
            'contact_no' => $request->contact_no,
            'status' => $request->status,
            'certificate' => $certificate,
        ]);

        return redirect()->route('employee.index')->with('success', 'employee created successfully.');
    }

    public function edit($id)
    {
        $item = Employee::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        return view('user.employee.edit_employee', compact('item'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'contractor_name' => 'required|string|max:255',
            'emp_no' => 'required|string|max:100',
            'employee_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'contact_no' => 'required|string|max:15',
        ]);

        $employee = Employee::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        if ($request->hasFile('certificate')) {
            if (!empty($employee->certificate)) {
                $oldImagePath = public_path('certificate/' . $employee->certificate);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file = $request->file('certificate');
            $certificate = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('certificate'), $certificate);
            $employee->certificate = $certificate;
        }
        $employee->update([
            'contractor_name' => $request->contractor_name,
            'emp_no' => $request->emp_no,
            'employee_name' => $request->employee_name,
            'designation' => $request->designation,
            'contact_no' => $request->contact_no,
            'status' => $request->status,
            'certificate' => $employee->certificate,
        ]);

        return redirect()->route('employee.index')->with('success', 'employee updated successfully.');
    }

    public function destroy($id)
    {
        $employee = Employee::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        if ($employee) {
            if (!empty($employee->certificate)) {
                $imagePath = public_path('certificate/' . $employee->certificate);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $employee->delete();
            return redirect()->route('employee.index')->with('success', 'employee deleted successfully.');
        }

        return redirect()->back()->with('error', 'employee not found.');

    }
}
