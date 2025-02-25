@extends('layouts.app')

@section('title', 'Books')

@section('content')

    <div class="title-container">
        <h1>Books List</h1>
        <button class="add-btn" onClick="addBookRedirection()">Add Book</button>
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-container">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <h3>Confirm Delete</h3>
            <p>Are you sure you want to delete this book?</p>

            <div class="modal-buttons">
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="delete-btn">Delete</button>
            </div>
        </div>
    </div>



    <script>

        function addBookRedirection() {
            window.location.href = "../books/add";
        }

        function openModal() {
            document.getElementById('deleteModal').style.display = 'block'; // Show modal
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none'; // Hide modal
        }
        
        // Close modal if user clicks outside it
        window.onclick = function(event) {
            let modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>

   
@endsection
