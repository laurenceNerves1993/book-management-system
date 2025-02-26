@extends('layouts.app')

@section('title', 'Books')

@section('content')

<div class="title-container">
        <h1>Book List</h1>
        <button class="add-btn" id="addBook">Add Book</button>
        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popup">
        <h2>Enter Book Title</h2>
        <form id="bookForm">
            <input type="text" id="bookName" placeholder="Enter title" required>
            <br><br>
            <button type="submit" class="submit-btn">Submit</button>
            <button type="button" id="closeButton" class="cancel-btn">Cancel</button>
        </form>
        </div>
        </div>
    

    <table border="1" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Description</th>
                <!-- <th>Cover image</th> -->
                <th>Book Released</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
            <tr>
                <td>{{ $book->id }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author->name ?? 'N/A' }}</td>
                <td>{{ $book->category->name ?? 'N/A' }}</td>
                <td>{{ $book->description ?? 'N/A' }}</td>
                <!-- <td>{{ $book->cover_image }}</td> -->
                <td>{{ $book->created_at->format('F d, Y | h:i A') }}</td>
                <td><button class="edit-btn" onclick="">Edit</button></td>
                <td><button class="delete-btn" onclick="openModal()">Delete</button></td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No books found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

   
@endsection
