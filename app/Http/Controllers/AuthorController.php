<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index() {
        // $authors = Author::all();
        $authors = Author::orderBy('id', 'desc')->get();
        return view('authors.index', compact('authors'));
    }

    public function store(Request $request) {
        $request->validate([ 'newAuthor' => 'required|string|max:255', ]); // For form validation
        $author = Author::create([ 'name' => $request->newAuthor, ]);
        return response()->json(['alertMessage' => 'Author added successfully!'], 201);
    }

    public function destroy($id) {
        $author = Author::find($id); // to get the author data
        if (!$author) {
            return response()->json(['alertMessage' => 'Author not found'], 404);
        } else {
            $author->delete(); // Database deletion
            return response()->json(['alertMessage' => 'Author deleted successfully!']);
        }
    }
    
    public function update(Request $request, $id) {
        $request->validate(['updateAuthor' => 'required|string|max:255']);
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['alertMessage' => 'Author not found'], 404);
        } else {
            $author->name = $request->updateAuthor;
            $author->save();

            return response()->json(['alertMessage' => 'Author updated successfully!']);
        }
        
    }

    
}