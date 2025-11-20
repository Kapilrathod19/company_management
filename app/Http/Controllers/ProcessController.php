<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Process;
use App\Models\ProcessMaster;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function itemList()
    {
        $items = Item::where('user_id', auth()->id())->latest()->get();
        return view('user.process.items', compact('items'));
    }

    public function index($itemId)
    {
        $item = Item::where('user_id', auth()->id())->findOrFail($itemId);

        $processes = Process::where('item_id', $itemId)
            ->orderBy('position')
            ->get();

        return view('user.process.index', compact('item', 'processes'));
    }

    public function create($itemId)
    {
        $item = Item::where('user_id', auth()->id())->findOrFail($itemId);
        return view('user.process.create', compact('item'));
    }

    public function store(Request $request, $itemId)
    {
        $request->validate([
            'process_name' => 'required',
            'details' => 'nullable',
        ]);

        $maxPosition = Process::where('item_id', $itemId)->max('position') ?? 0;

        Process::create([
            'item_id' => $itemId,
            'process_name' => $request->process_name,
            'details' => $request->details,
            'position' => $maxPosition + 1,
        ]);

        return redirect()->route('process.index', $itemId)
            ->with('success', 'Process added successfully!');
    }

    public function edit($itemId, $id)
    {
        $item = Item::where('user_id', auth()->id())->findOrFail($itemId);
        $process = Process::findOrFail($id);

        return view('user.process.edit', compact('item', 'process'));
    }

    public function update(Request $request, $itemId, $id)
    {
        $request->validate([
            'process_name' => 'required',
            'details' => 'nullable',
        ]);

        $process = Process::findOrFail($id);
        $process->update($request->only('process_name', 'details'));

        return redirect()->route('process.index', $itemId)
            ->with('success', 'Process updated successfully!');
    }

    public function destroy($itemId, $id)
    {
        Process::findOrFail($id)->delete();

        return redirect()->route('process.index', $itemId)
            ->with('success', 'Process deleted successfully!');
    }

    public function sort(Request $request, $itemId)
    {
        foreach ($request->order as $position => $id) {
            Process::where('id', $id)->update(['position' => $position + 1]);
        }

        return response()->json(['status' => 'success']);
    }
    public function getProcesses($itemId)
    {
        $processes = Process::where('item_id', $itemId)
            ->orderBy('position')
            ->get(['process_name', 'details']);

        return response()->json($processes);
    }


    public function process_master_index()
    {
        $processMasters = ProcessMaster::where('user_id', auth()->id())->latest()->get();
        return view('user.process_master.index', compact('processMasters'));
    }

    public function process_master_create()
    {
        return view('user.process_master.create');
    }

    public function process_master_store(Request $request)
    {
        $request->validate([
            'process_number' => 'required',
            'process_name' => 'required',
        ]);

        ProcessMaster::create([
            'user_id' => auth()->id(),
            'process_number' => $request->process_number,
            'process_name' => $request->process_name,
        ]);

        return redirect()->route('process_master.index')->with('success', 'Process Master added successfully!');
    }

    public function process_master_edit($id)
    {
        $processMaster = ProcessMaster::where('user_id', auth()->id())->findOrFail($id);
        return view('user.process_master.edit', compact('processMaster'));
    }

    public function process_master_update(Request $request, $id)
    {
        $request->validate([
            'process_number' => 'required',
            'process_name' => 'required',
        ]);

        $processMaster = ProcessMaster::findOrFail($id);
        $processMaster->update($request->only('process_number', 'process_name'));

        return redirect()->route('process_master.index')->with('success', 'Process Master updated successfully!');
    }

    public function process_master_destroy($id)
    {
        ProcessMaster::where('user_id', auth()->id())->findOrFail($id)->delete();

        return redirect()->route('process_master.index')->with('success', 'Process Master deleted successfully!');
    }
}
