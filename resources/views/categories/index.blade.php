@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="title-container">
        <h1>Category List</h1>
        <button class="add-btn" id="addCategory">Add Category</button>
        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popup">
        <h2>Enter Category Name</h2>
        <form id="categoryForm">
            <input type="text" id="categoryName" placeholder="Enter name" required>
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
                <th>Created At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category) 
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->created_at->format('F d, Y | h:i A') }}</td>
                <td>
                    <button class="edit-btn" data-id="{{ $category->id }}">Edit</button>
                    <button class="submit-btn" data-id="{{ $category->id }}" style="display: none;">Update</button>
                </td>
                <td><button class="delete-btn" data-id="{{ $category->id }}">Delete</button></td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No categories found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        const addCategory= document.getElementById('addCategory');
        const popup = document.getElementById('popup');
        const overlay = document.getElementById('overlay');
        const closeButton = document.getElementById('closeButton');
        const categoryForm = document.getElementById('categoryForm');
        const categoryName = document.getElementById('categoryName');

        addCategory.addEventListener('click', () => {
            popup.style.display = 'block';
            overlay.style.display = 'block';
        });

        closeButton.addEventListener('click', () => {
            popup.style.display = 'none';
            overlay.style.display = 'none';
            categoryName.value = '';
        });

        overlay.addEventListener('click', () => {
            popup.style.display = 'none';
            overlay.style.display = 'none';
        });

        // Adding category
        categoryForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent loading
            const newCategory = categoryName.value; // Create temporary container and store value
            const response = await fetch("{{ route('categories.store') }}", {
                method: "POST",//use POST method for getting the value in the input field
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ newCategory })
            });
            const data = await response.json(); // Collect data from backend side using .json
            
            // Condition if the response is success
            if (response.ok) {
                alert(data.alertMessage);
                location.reload(); // Refresh to show the new author
            } else {
                alert("Failed to add category!.");
            }

            popup.style.display = 'none';
            overlay.style.display = 'none';
            categoryName.value = '';
        });

        // Deleting category
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                const categoryId = e.target.getAttribute('data-id');
                const confirmDelete = confirm("Are you sure you want to delete this category?");
                if (!confirmDelete) return;

                const response = await fetch(`/categories/${categoryId}`, {
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
                    alert("Failed to delete category.");
                }
            });
        });

        // Updating author
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const row = e.target.closest('tr'); // Get the current row
                const categoryId = e.target.getAttribute('data-id');
                const nameCell = row.querySelector('td:nth-child(2)'); // Get the Name column
                const editButton = row.querySelector('.edit-btn');
                const saveButton = row.querySelector('.submit-btn');

                // Convert name to an input field
                const currentName = nameCell.textContent.trim();
                nameCell.innerHTML = `<input type="text" value="${currentName}" class="edit-input" data-id="${categoryId}">`;

                // Show the Save button and hide the Edit button
                editButton.style.display = 'none';
                saveButton.style.display = 'inline-block';
            });
        });

        // Saving the updated name
        document.querySelectorAll('.submit-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                const row = e.target.closest('tr');
                const categoryId = e.target.getAttribute('data-id');
                const inputField = row.querySelector('.edit-input');
                const updatedName = inputField.value.trim();
                const editButton = row.querySelector('.edit-btn');
                const saveButton = row.querySelector('.submit-btn');

                if (!updatedName) {
                    alert("Category name cannot be empty!");
                    return;
                }

                const response = await fetch(`/categories/${categoryId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({ updateCategory: updatedName })
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.alertMessage);
                    row.querySelector('td:nth-child(2)').innerHTML = updatedName; // Replace input with text
                    editButton.style.display = 'inline-block';
                    saveButton.style.display = 'none';
                } else {
                    alert("Failed to update category.");
                }
            });
        });


    </script>
@endsection
