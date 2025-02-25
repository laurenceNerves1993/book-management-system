@extends('layouts.app')

@section('title', 'Authors')

@section('content')

    <div class="title-container">
        <h1>Author List</h1>
        <button class="add-btn" id="addAuthor">Add Author</button>
        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popup">
        <h2>Enter Author Name</h2>
        <form id="authorForm">
            <input type="text" id="authorName" placeholder="Enter name" required>
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
                <th>Name</th>
                <th>Date Upload</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($authors as $author)
            <tr>
                <td>{{ $author->id }}</td>
                <td>{{ $author->name }}</td>
                <td>{{ $author->created_at->format('F d, Y | h:i A') }}</td>
                <td><button class="edit-btn">Edit</button></td>
                <td><button class="delete-btn">Delete</button></td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No authors found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        const addAuthor = document.getElementById('addAuthor');
        const popup = document.getElementById('popup');
        const overlay = document.getElementById('overlay');
        const closeButton = document.getElementById('closeButton');
        const authorForm = document.getElementById('authorForm');
        const authorName = document.getElementById('authorName');

        addAuthor.addEventListener('click', () => {
            popup.style.display = 'block';
            overlay.style.display = 'block';
        });

        closeButton.addEventListener('click', () => {
            popup.style.display = 'none';
            overlay.style.display = 'none';
        });

        overlay.addEventListener('click', () => {
            popup.style.display = 'none';
            overlay.style.display = 'none';
        });

        authorForm.addEventListener('submit', (e) => {
            e.preventDefault();
            console.log("Submitted Name:", authorName.value);
            popup.style.display = 'none';
            overlay.style.display = 'none';
            authorName.value = '';
        });
    </script>
@endsection
