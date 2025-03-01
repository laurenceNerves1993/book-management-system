@extends('layouts.app')

@section('title', 'Books')

@section('content')
    <div class="title-container">
        <h1>Book List</h1>
        <button class="add-btn" id="addBook">Add Book</button>
        <!-- Add Book Modal -->
        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popup">
            <h2>Add Book</h2>
            <form id="bookForm" class="book-form">
                <div class="form-group">
                    <input type="text" id="bookName" placeholder="Enter title" required>
                </div>
                
                <div class="form-group">
                    <select id="author" required>
                        <option value="" disabled selected>Select an author</option>
                    </select>
                </div>

                <div class="form-group">
                    <select id="category" required>
                        <option value="" disabled selected>Select category</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <textarea id="description" placeholder="Enter description" rows="4" required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Submit</button>
                    <button type="button" id="closeButton" class="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <table class="books-table" border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Description</th>
                <th>Book Released</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
            <tr data-book-id="{{ $book->id }}">
                <td>{{ $book->id }}</td>
                <td>{{ $book->title }}</td>
                <td data-author-id="{{ $book->author->id ?? '' }}">{{ $book->author->name ?? 'N/A' }}</td>
                <td data-category-id="{{ $book->category->id ?? '' }}">{{ $book->category->name ?? 'N/A' }}</td>
                <td>{{ $book->description ?? 'N/A' }}</td>
                <td>{{ $book->created_at->format('F d, Y | h:i A') }}</td>
                <td>
                    <button class="edit-btn" data-id="{{ $book->id }}">Edit</button>
                    <button class="submit-btn" data-id="{{ $book->id }}" style="display: none;">Update</button>
                </td>
                <td><button class="delete-btn" data-id="{{ $book->id }}">Delete</button></td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="no-data">No books found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        // Constants and State Management
        const STATE = {
            authors: [],
            categories: [],
            isEditing: false
        };

        const ELEMENTS = {
            addBook: document.getElementById('addBook'),
            bookForm: document.getElementById('bookForm'),
            popup: document.getElementById('popup'),
            overlay: document.getElementById('overlay'),
            closeButton: document.getElementById('closeButton'),
            bookName: document.getElementById('bookName'),
            description: document.getElementById('description'),
            author: document.getElementById('author'),
            category: document.getElementById('category')
        };

        const ENDPOINTS = {
            authors: '/api/authors',
            categories: '/api/categories',
            books: '/books'
        };

        const showAlert = (message, type = 'info') => {
            alert(message);
        };

        const toggleModal = (show = true) => {
            ELEMENTS.popup.style.display = show ? 'block' : 'none';
            ELEMENTS.overlay.style.display = show ? 'block' : 'none';
            
            if (!show) {
                resetForm();
            }
        };

        const resetForm = () => {
            ELEMENTS.bookForm.reset();
            STATE.isEditing = false;
        };

        const createSelectOptions = (items, selectElement) => {
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                selectElement.appendChild(option);
            });
        };

        // API Functions
        const fetchData = async (url) => {
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Network response was not ok');
                return await response.json();
            } catch (error) {
                console.error('Error fetching data:', error);
                showAlert('Failed to load data. Please refresh the page.', 'error');
                return [];
            }
        };

        const initializeData = async () => {
            const [authorsData, categoriesData] = await Promise.all([
                fetchData(ENDPOINTS.authors),
                fetchData(ENDPOINTS.categories)
            ]);

            STATE.authors = authorsData;
            STATE.categories = categoriesData;

            createSelectOptions(STATE.authors, ELEMENTS.author);
            createSelectOptions(STATE.categories, ELEMENTS.category);
        };

        // Event Handlers
        const handleSubmit = async (e) => {
            e.preventDefault();

            const formData = {
                title: ELEMENTS.bookName.value.trim(),
                author_id: ELEMENTS.author.value,
                category_id: ELEMENTS.category.value,
                description: ELEMENTS.description.value.trim(),
                cover_image: "Null"
            };

            try {
                const response = await fetch("{{ route('books.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    showAlert(data.alertMessage, 'success');
                    location.reload();
                } else {
                    throw new Error(data.message || 'Failed to add book');
                }
            } catch (error) {
                showAlert(error.message, 'error');
            } finally {
                toggleModal(false);
            }
        };

        const handleEdit = (e) => {
            const row = e.target.closest('tr');
            const bookId = e.target.dataset.id;
            
            const cells = {
                title: row.querySelector('td:nth-child(2)'),
                author: row.querySelector('td:nth-child(3)'),
                category: row.querySelector('td:nth-child(4)'),
                description: row.querySelector('td:nth-child(5)')
            };

            const currentValues = {
                title: cells.title.textContent.trim(),
                authorId: cells.author.dataset.authorId,
                categoryId: cells.category.dataset.categoryId,
                description: cells.description.textContent.trim()
            };

            // Create edit fields
            cells.title.innerHTML = `<input type="text" class="edit-title" value="${currentValues.title}" required>`;
            cells.author.innerHTML = createSelect(STATE.authors, currentValues.authorId, 'edit-author');
            cells.category.innerHTML = createSelect(STATE.categories, currentValues.categoryId, 'edit-category');
            cells.description.innerHTML = `<textarea class="edit-description" required>${currentValues.description}</textarea>`;

            toggleEditButtons(row, true);
        };

        const handleUpdate = async (e) => {
            const row = e.target.closest('tr');
            const bookId = e.target.dataset.id;
            
            const updatedData = {
                title: row.querySelector('.edit-title').value.trim(),
                author_id: row.querySelector('.edit-author').value,
                category_id: row.querySelector('.edit-category').value,
                description: row.querySelector('.edit-description').value.trim()
            };

            if (!updatedData.title) {
                showAlert('Book title cannot be empty!', 'error');
                return;
            }

            try {
                const response = await fetch(`${ENDPOINTS.books}/${bookId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(updatedData)
                });

                const data = await response.json();

                if (response.ok) {
                    updateRowDisplay(row, updatedData);
                    showAlert(data.alertMessage, 'success');
                } else {
                    throw new Error(data.message || 'Failed to update book');
                }
            } catch (error) {
                showAlert(error.message, 'error');
            }
        };

        const handleDelete = async (e) => {
            const bookId = e.target.dataset.id;
            if (!confirm('Are you sure you want to delete this book?')) return;

            try {
                const response = await fetch(`${ENDPOINTS.books}/${bookId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    showAlert(data.alertMessage, 'success');
                    e.target.closest('tr').remove();
                } else {
                    throw new Error(data.message || 'Failed to delete book');
                }
            } catch (error) {
                showAlert(error.message, 'error');
            }
        };

        // Helper Functions
        const createSelect = (items, selectedId, className) => {
            let select = `<select class="${className}">`;
            items.forEach(item => {
                select += `<option value="${item.id}" ${item.id == selectedId ? 'selected' : ''}>${item.name}</option>`;
            });
            return select + '</select>';
        };

        const toggleEditButtons = (row, isEditing) => {
            row.querySelector('.edit-btn').style.display = isEditing ? 'none' : 'inline-block';
            row.querySelector('.submit-btn').style.display = isEditing ? 'inline-block' : 'none';
        };

        const updateRowDisplay = (row, data) => {
            const selectedAuthor = STATE.authors.find(a => a.id == data.author_id);
            const selectedCategory = STATE.categories.find(c => c.id == data.category_id);
            
            row.querySelector('td:nth-child(2)').textContent = data.title;
            row.querySelector('td:nth-child(3)').textContent = selectedAuthor?.name || 'N/A';
            row.querySelector('td:nth-child(3)').dataset.authorId = data.author_id;
            row.querySelector('td:nth-child(4)').textContent = selectedCategory?.name || 'N/A';
            row.querySelector('td:nth-child(4)').dataset.categoryId = data.category_id;
            row.querySelector('td:nth-child(5)').textContent = data.description;
            
            toggleEditButtons(row, false);
        };

        // Event Listeners
        document.addEventListener('DOMContentLoaded', initializeData);
        
        ELEMENTS.addBook.addEventListener('click', () => toggleModal(true));
        ELEMENTS.closeButton.addEventListener('click', () => toggleModal(false));
        ELEMENTS.overlay.addEventListener('click', () => toggleModal(false));
        ELEMENTS.bookForm.addEventListener('submit', handleSubmit);

        document.querySelectorAll('.edit-btn').forEach(btn => 
            btn.addEventListener('click', handleEdit));
        
        document.querySelectorAll('.submit-btn').forEach(btn => 
            btn.addEventListener('click', handleUpdate));
        
        document.querySelectorAll('.delete-btn').forEach(btn => 
            btn.addEventListener('click', handleDelete));
    </script>

    <style>
        .books-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .books-table th,
        .books-table td {
            padding: 10px;
            text-align: left;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .edit-title,
        .edit-author,
        .edit-category,
        .edit-description {
            width: 100%;
            padding: 4px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .edit-description {
            min-height: 60px;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #666;
        }
    </style>
@endsection
