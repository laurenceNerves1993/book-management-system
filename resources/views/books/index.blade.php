@extends('layouts.app')

@section('title', 'Books')

@section('content')

    <div class="title-container">
        <h1>Book List</h1>
        <button class="add-btn" id="addBook">Add Book</button>
        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popup">
            <h2>Add Book</h2>
            <form id="bookForm">
                <input type="text" id="bookName" placeholder="Enter title" required>
                <br><br>
            
                <select id="author" required>
                    <option value="" disabled selected>Select an author</option>
                </select>
                <br><br>

                <select id="category" required>
                    <option value="" disabled selected>Select category</option>
                </select>
                <br><br>
            
                <textarea id="description" placeholder="Enter description" rows="4" required></textarea>
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

    <script>
        const addBook = document.getElementById('addBook');
        const bookForm = document.getElementById('bookForm');

        addBook.addEventListener('click', () => {
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

        document.addEventListener("DOMContentLoaded", async function () {
            try {
                const response = await fetch('/api/authors'); // Use updated API endpoint
                const data = await response.json();
                const authorSelect = document.getElementById('author');

                data.forEach(author => {
                    const option = document.createElement('option');
                    option.value = author.id;
                    option.textContent = author.name;
                    authorSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching authors:', error);
            }
        });

        document.addEventListener("DOMContentLoaded", async function () {
            try {
                const response = await fetch('/api/categories'); // Use updated API endpoint
                const data = await response.json();
                const categorySelect = document.getElementById('category');

                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        });

        bookForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent loading or refresh page

            // Store values in an object
            const formData = {
                title: document.getElementById("bookName").value,
                author_id: document.getElementById("author").value,
                category_id: document.getElementById("category").value,
                description: document.getElementById("description").value,
                cover_image: "Null"
            };

            const response = await fetch("{{ route('books.store') }}", {
                method: "POST",//use POST method for getting the value in the input field
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify( formData )
            });

            const data = await response.json();
            

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



    </script>

   
@endsection
