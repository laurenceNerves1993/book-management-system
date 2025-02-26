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
}
