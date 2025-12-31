<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Permission;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function index()
    {
        $documents = Document::where('user_id', auth()->id())->latest()->get();
        $permissions = Permission::where('user_id', auth()->id())->get()->keyBy('module');
        return view('user.document.list_document', compact('documents', 'permissions'));
    }

    public function create()
    {
        return view('user.document.add_document');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document' => 'required|file',
        ]);

        $document = new Document();
        $document->user_id = auth()->id();
        $document->title = $request->input('title');

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documents'), $filename);
            $document->document = $filename;
        }

        $document->save();

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');
    }

    public function edit($id)
    {
        $document = Document::where('user_id', auth()->id())->findOrFail($id);
        return view('user.document.edit_document', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document' => 'nullable|file',
        ]);

        $document = Document::where('user_id', auth()->id())->findOrFail($id);
        $document->title = $request->input('title');

        if ($request->hasFile('document')) {
            if ($document->document && file_exists(public_path('documents/' . $document->document))) {
            unlink(public_path('documents/' . $document->document));
            }

            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documents'), $filename);
            $document->document = $filename;
        }

        $document->save();

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy($id)
    {
        $document = Document::where('user_id', auth()->id())->findOrFail($id);

        if ($document->document && file_exists(public_path('documents/' . $document->document))) {
            unlink(public_path('documents/' . $document->document));
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
