@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="title-container">

    <button class="add-btn" onClick="addCategoryRedirection()">Add Category</button>
    </div>

    <h1>Categories List</h1>

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
                <td><button class="edit-btn">Edit</button></td>
                <td><button class="delete-btn">Delete</button></td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No categories found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
