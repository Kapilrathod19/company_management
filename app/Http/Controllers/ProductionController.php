<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Permission;
use App\Models\Process;
use App\Models\Production;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with('employee', 'salesOrder', 'processDetails')->where('user_id', auth()->id())->latest()->get();
        $permissions = Permission::where('user_id', auth()->id())->get()->keyBy('module');
        return view('user.production.list_production', compact('productions', 'permissions'));
    }

    public function create()
    {
        $employees = Employee::where('user_id', auth()->id())->where('status', 'Active')
            ->latest()
            ->get();

        $salesorders = SalesOrder::where('user_id', auth()->id())
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')
                    ->from('sales_orders')
                    ->where('user_id', auth()->id())
                    ->groupBy('unit_no');
            })->orderBy('unit_no')->get();

        return view('user.production.create_production', compact('employees', 'salesorders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sr_no'         => 'required',
            'date'          => 'required|date',
            'employee_id'   => 'required',
            'unit_no'       => 'required',
            'component_no'  => 'required',
            'process'       => 'required',
            'qty'           => 'required|numeric|min:1',
            'weight'        => 'required|numeric|min:0.01',
            'total_weight'  => 'required|numeric|min:0.01',
            'remark'        => 'nullable|string'
        ]);

        $salesorder = SalesOrder::where('user_id', auth()->id())
            ->with('item')
            ->findOrFail($request->unit_no);

        $process = Process::where('item_id', $salesorder->part_no)
            ->where('process_id', $request->process)
            ->first();

        if (!$process) {
            return back()->with('error', 'Invalid process selected.')->withInput();
        }

        // Save Production
        $production = new Production();
        $production->user_id       = auth()->id();
        $production->sr_no         = $request->sr_no;
        $production->date          = $request->date;
        $production->employee_name   = $request->employee_id;
        $production->unit_no = $request->unit_no;
        $production->component_no  = $request->component_no;
        $production->process    = $request->process;
        $production->qty           = $request->qty;
        $production->weight        = $request->weight;
        $production->total_weight  = $request->total_weight;
        $production->description   = $request->description;
        $production->remark        = $request->remark;
        $production->save();

        return redirect()->route('production.index')->with('success', 'Production added successfully.');
    }

    public function edit($id)
    {
        $production = Production::where('user_id', auth()->id())->findOrFail($id);

        $employees = Employee::where('user_id', auth()->id())
            ->where('status', 'Active')
            ->latest()
            ->get();

        $salesorders = SalesOrder::where('user_id', auth()->id())
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')
                    ->from('sales_orders')
                    ->where('user_id', auth()->id())
                    ->groupBy('unit_no');
            })
            ->orderBy('unit_no')->get();

        return view('user.production.edit_production', compact(
            'production',
            'employees',
            'salesorders'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sr_no'         => 'required',
            'date'          => 'required|date',
            'employee_id'   => 'required',
            'unit_no'       => 'required',
            'component_no'  => 'required',
            'process'       => 'required',
            'qty'           => 'required|numeric|min:1',
            'weight'        => 'required|numeric|min:0.01',
            'total_weight'  => 'required|numeric|min:0.01',
            'remark'        => 'nullable|string'
        ]);

        $production = Production::where('user_id', auth()->id())->findOrFail($id);

        $production->sr_no         = $request->sr_no;
        $production->date          = $request->date;
        $production->employee_name = $request->employee_id;
        $production->unit_no       = $request->unit_no;
        $production->component_no  = $request->component_no;
        $production->process       = $request->process;
        $production->qty           = $request->qty;
        $production->weight        = $request->weight;
        $production->total_weight  = $request->total_weight;
        $production->description   = $request->description;
        $production->remark        = $request->remark;
        $production->save();

        return redirect()->route('production.index')->with('success', 'Production updated successfully.');
    }

    public function destroy($id)
    {
        $production = Production::where('user_id', auth()->id())->findOrFail($id);
        $production->delete();

        return redirect()->route('production.index')->with('success', 'Production deleted successfully.');
    }

    public function getComponents($id)
    {
        $salesorder = SalesOrder::where('user_id', auth()->id())->findOrFail($id);

        return response()->json([
            'component_no' => $salesorder->item->part_number,
        ]);
    }

    public function getComponentDetails($id)
    {
        $salesorder = SalesOrder::where('user_id', auth()->id())
            ->with([
                'item.processes.processMaster'
            ])
            ->findOrFail($id);

        $processes = $salesorder->item->processes->map(function ($p) {
            return [
                'id' => $p->processMaster->id,
                'process_number' => $p->processMaster->process_number,
                'process_name'   => $p->processMaster->process_name,
            ];
        });

        return response()->json([
            'details' => [
                'qty'          => $salesorder->qty,
                'weight'       => $salesorder->weight,
                'total_weight' => $salesorder->total_weight,
                'description'  => $salesorder->description,
            ],
            'processes' => $processes
        ]);
    }
}
