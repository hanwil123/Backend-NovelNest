<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/dashboard/upload-books', function () {
    return Inertia::render('UploadBooks');
})->name('dashboard.upload-books');
Route::get('/dashboard/upload-books', function () {
    return Inertia::render('UploadBooks');
})->name('dashboard.upload-books');

Route::get('/dashboard/edit-books/{id}', function ($id) {
    return Inertia::render('EditBooks', ['bookId' => $id]);
})->name('dashboard.edit-books');


Route::get('/books/delete/{id}', function ($id) {
    return Inertia::render('DeleteAlert', ['id' => $id]);
})->name('books.delete');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
