<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeWork;
use App\Models\Permission;
use Illuminate\Http\Request;

class EmployeeWorkController extends Controller
{
    public function index()
    {
        $employees = EmployeeWork::with('employee')->where('user_id', auth()->id())->latest()->get();
        $permissions = Permission::where('user_id', auth()->id())->get()->keyBy('module');
        return view('user.employee_work.list_employee_work', compact('employees', 'permissions'));
    }


    public function create()
    {
        $employees = Employee::where('user_id', auth()->id())->where('status', 'Active')->latest()->get();
        return view('user.employee_work.add_employee_work', compact('employees'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'employee_id' => 'required|exists:employees,id',
            'work_done' => 'required|string',
            'weight' => 'required|numeric',
        ]);

        EmployeeWork::create([
            'user_id' => auth()->id(),
            'date' => $request->date,
            'employee_id' => $request->employee_id,
            'work_done' => $request->work_done,
            'weight' => $request->weight,
        ]);

        return redirect()->route('employee_work.index')->with('success', 'Employee work added successfully.');
    }

    public function edit($id)
    {
        $item = EmployeeWork::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $employees = Employee::where('user_id', auth()->id())->where('status', 'Active')->latest()->get();
        return view('user.employee_work.edit_employee_work', compact('item', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'work_done' => 'required|string',
            'weight' => 'required|numeric',
        ]);

        $employee = EmployeeWork::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $employee->update([
            'date' => $request->date,
            'employee_id' => $request->employee_id,
            'work_done' => $request->work_done,
            'weight' => $request->weight,
        ]);

        return redirect()->route('employee_work.index')->with('success', 'Employee work updated successfully.');
    }

    public function destroy($id)
    {
        $employee = EmployeeWork::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $employee->delete();

        return redirect()->route('employee_work.index')->with('success', 'Employee work deleted successfully.');
    }
}
