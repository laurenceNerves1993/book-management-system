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
                <td>
                    <button class="edit-btn" data-id="{{ $author->id }}">Edit</button>
                    <button class="submit-btn" data-id="{{ $author->id }}" style="display: none;">Update</button>
                </td>
                <td><button class="delete-btn" data-id="{{ $author->id }}">Delete</button></td>
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
            authorName.value = '';
        });

        overlay.addEventListener('click', () => {
            popup.style.display = 'none';
            overlay.style.display = 'none';
        });

        // Adding author
        authorForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent loading
            const newAuthor = authorName.value; // Create temporary container and store value
            const response = await fetch("{{ route('authors.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ newAuthor })
            });
            const data = await response.json(); // Collect data from backend side using .json
            
            // Condition if the response is success
            if (response.ok) {
                alert(data.alertMessage);
                location.reload(); // Refresh to show the new author
            } else {
                alert("Failed to add author.");
            }

            popup.style.display = 'none';
            overlay.style.display = 'none';
            authorName.value = '';
        });

        // Deleting author
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                const authorId = e.target.getAttribute('data-id');
                const confirmDelete = confirm("Are you sure you want to delete this author?");
                if (!confirmDelete) return;

                const response = await fetch(`/authors/${authorId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.alertMessage);
                    location.reload(); // Refresh the page to update the list
                } else {
                    alert("Failed to delete author.");
                }
            });
        });

        // Updating author
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const row = e.target.closest('tr'); // Get the current row
                const authorId = e.target.getAttribute('data-id');
                const nameCell = row.querySelector('td:nth-child(2)'); // Get the Name column
                const editButton = row.querySelector('.edit-btn');
                const saveButton = row.querySelector('.submit-btn');

                // Convert name to an input field
                const currentName = nameCell.textContent.trim();
                nameCell.innerHTML = `<input type="text" value="${currentName}" class="edit-input" data-id="${authorId}">`;

                // Show the Save button and hide the Edit button
                editButton.style.display = 'none';
                saveButton.style.display = 'inline-block';
            });
        });

        // Saving the updated name
        document.querySelectorAll('.submit-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                const row = e.target.closest('tr');
                const authorId = e.target.getAttribute('data-id');
                const inputField = row.querySelector('.edit-input');
                const updatedName = inputField.value.trim();
                const editButton = row.querySelector('.edit-btn');
                const saveButton = row.querySelector('.submit-btn');

                if (!updatedName) {
                    alert("Author name cannot be empty!");
                    return;
                }

                const response = await fetch(`/authors/${authorId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({ updateAuthor: updatedName })
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.alertMessage);
                    row.querySelector('td:nth-child(2)').innerHTML = updatedName; // Replace input with text
                    editButton.style.display = 'inline-block';
                    saveButton.style.display = 'none';
                } else {
                    alert("Failed to update author.");
                }
            });
        });


    </script>
@endsection
