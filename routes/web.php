<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;

// Book Route
Route::get('/books', [BookController::class, 'index'])->name('books.index');

// Author Route
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store'); // add data always POST method
Route::delete('/authors/{id}', [AuthorController::class, 'destroy'])->name('authors.destroy'); // DELETE method to remove data
Route::put('/authors/{id}', [AuthorController::class, 'update'])->name('authors.update'); // PUT method for updating data

// Category Route
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store'); // add data always POST method
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy'); // DELETE method to remove data
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update'); // PUT method for updating data

 
// Index Route
Route::get('/', function () {
    return view('layouts.app');
});
