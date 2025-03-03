<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
 
    public function index() {
        // $books = Book::all();
        $books = Book::with(['author', 'category'])->orderBy('id', 'desc')->get();
        return view("books.index", compact('books'));
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id', 
            'category_id' => 'required|exists:categories,id', 
            'description' => 'required|string|min:10',
            'cover_image' => 'required',
        ]);

        $book = Book::create($validatedData);
        return response()->json(['alertMessage' => 'Book added successfully!'], 201);
    }

    public function destroy($id) {
        $book = Book::find($id); // to get the author data
        if (!$book) {
            return response()->json(['alertMessage' => 'Book not found'], 404);
        } else {
            $book->delete(); // Database deletion
            return response()->json(['alertMessage' => 'Book deleted successfully!']);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
        ]);

        // Retrieve the book by ID
        $book = Book::find($id);

        // Check if the book exists
        if (!$book) {
            return response()->json(['alertMessage' => 'Book not found!'], 404);
        }

        // Update the book
        $book->update($request->all());

        return response()->json(['alertMessage' => 'Book updated successfully!', 'book' => $book]);
    }
}
