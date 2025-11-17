<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Process;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::where('user_id', auth()->id())->latest()->get();
        return view('user.item.list_item', compact('items'));
    }


    public function create()
    {
        return view('user.item.add_item');
    }


    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'part_number' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'unit' => 'required|string|max:50',
        ]);

        Item::create([
            'user_id' => auth()->id(),
            'category' => $request->category,
            'part_number' => $request->part_number,
            'description' => $request->description,
            'unit' => $request->unit,
            'quantity' => $request->quantity,
            'weight' => $request->weight,
        ]);

        return redirect()->route('item.index')->with('success', 'Item created successfully.');
    }

    public function edit($id)
    {
        $item = Item::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        return view('user.item.edit_item', compact('item'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'part_number' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'unit' => 'required|string|max:50',
        ]);

        $item = Item::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        $item->update([
            'category' => $request->category,
            'part_number' => $request->part_number,
            'description' => $request->description,
            'unit' => $request->unit,
            'quantity' => $request->quantity,
            'weight' => $request->weight,
        ]);

        return redirect()->route('item.index')->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $Item = item::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        Process::where('item_id', $Item->id)->delete();
        $Item->delete();

        return redirect()->route('item.index')->with('success', 'Item deleted successfully.');
    }
}
