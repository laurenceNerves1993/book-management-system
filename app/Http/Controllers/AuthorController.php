<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return view('authors.index', compact('authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $author = Author::create([
            'name' => $request->name,
        ]);

        return response()->json(['success' => 'Author added successfully.', 'author' => $author]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $author = Author::findOrFail($id);
        $author->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => 'Author updated successfully.', 'author' => $author]);
    }

    public function destroy($id)
    {
        Author::findOrFail($id)->delete();
        return response()->json(['success' => 'Author deleted successfully.']);
    }
}