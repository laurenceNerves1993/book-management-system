<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
 
Route::get('/', function () {
    return view('layouts.app');
});

Route::get('/books/add', function () {
    return view('books.add');
});
