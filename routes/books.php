<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/api/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::post('/api/books', [BookController::class, 'store'])->name('books.store');
Route::get('/api/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/api/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::post('/api/books/{book}', [BookController::class, 'update'])->name('books.update');
Route::delete('/api/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
Route::get('/api/categories', [BookController::class, 'getCategories']);


require __DIR__.'/bookmark.php';

