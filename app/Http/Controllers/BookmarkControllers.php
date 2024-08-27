<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookmarkControllers extends Controller
{
    /**
     * Display a listing of the bookmarks for the authenticated user.
     */
    public function index()
    {
        // Retrieve the currently authenticated user's ID
        $userId = auth()->user()->id;

        // Retrieve all bookmarks for the authenticated user
        $bookmarks = Bookmark::with('book') // Eager load the related book data
            ->where('user_id', $userId) // Filter by user_id
            ->get();

        // Return the bookmarks as a JSON response
        return response()->json($bookmarks);
    }


    /**
     * Store a newly created bookmark in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'page_number' => 'required|integer',
        ]);

        $bookId = $request->book_id;
        $pageNumber = $request->page_number;

        // Mendapatkan jumlah halaman buku
        $book = Book::findOrFail($bookId);
        $pageCount = $book->halaman;

        // Memeriksa apakah page_number melebihi jumlah halaman
        if ($pageNumber > $pageCount) {
            return response()->json(['error' => 'Halaman tersebut tidak ada'], 400);
        }

        $userId = auth()->user()->id;  // ID user yang sedang login

        $bookmark = Bookmark::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'page_number' => $pageNumber,
        ]);

        return response()->json($bookmark, 201);
    }
    /**
     * Remove the specified bookmark from storage.
     */
    public function destroy($id)
    {
        $bookmark = Bookmark::findOrFail($id);

        if ($bookmark->user_id != auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $bookmark->delete();

        return response()->json(['message' => 'Bookmark deleted successfully.']);
    }


    /**
     * Get bookmarks for a specific book.
     */
    public function getBookmarksByBook($bookId)
    {
        $userId = Auth::id();
        $bookmarks = Bookmark::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->get();

        return response()->json($bookmarks);
    }
}
