<?php

use App\Http\Controllers\BookmarkControllers;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('/bookmarks', [BookmarkControllers::class, 'index']);
    Route::post('/api/bookmarks', [BookmarkControllers::class, 'store']);;
    Route::delete('/bookmarks/{id}', [BookmarkControllers::class, 'destroy']);
    Route::get('/books/{bookId}/bookmarks', [BookmarkControllers::class, 'getBookmarksByBook']);
});

