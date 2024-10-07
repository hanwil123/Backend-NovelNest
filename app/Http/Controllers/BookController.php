<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\FacadesLog;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')->paginate(10);
        return response($books);
    }
    public function getAllCategories()
    {
        $categories = Category::all();
        return response($categories);
    }

    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'sinopsis' => 'required|string',
                'isibuku' => 'required|file|mimes:pdf',
                'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle PDF Upload
            $pdfFile = $request->file('isibuku');
            $pdfPath = $pdfFile->store('books', 'public');

            // Parse PDF to get the number of pages
            $parser = new Parser();
            $pdf = $parser->parseFile(Storage::disk('public')->path($pdfPath));
            $pageCount = count($pdf->getPages());

            // Handle Cover Image Upload
            $coverImage = $request->file('cover_image');
            $coverImagePath = $coverImage->store('cover_images', 'public');

            // Create the book entry
            $book = new Book();
            $book->judul = $request->judul;
            $book->penulis = $request->penulis;
            $book->category_id = $request->category_id;
            $book->sinopsis = $request->sinopsis;
            $book->isibuku = $pdfPath;
            $book->cover_image = $coverImagePath;
            $book->halaman = $pageCount;
            $book->rating = 0; // Default rating
            $book->save();

            return response()->json(['success' => 'Book uploaded successfully.', $book]);
        } catch (\Exception $e) {
            Log::error('Failed to upload book: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to upload book'], 500);
        }
    }

    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);
            return response()->json($book);
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error fetching book: " . $e->getMessage());
            return response()->json(['error' => 'Book not found'], 404);
        }
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);

            $validatedData = $request->validate([
                'judul' => 'nullable|string|max:255',
                'penulis' => 'nullable|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'sinopsis' => 'nullable|string',
                'isibuku' => 'nullable|string',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'halaman' => 'nullable|integer',
                'rating' => 'nullable|numeric',
                'audio_length' => 'nullable|string',
            ]);

            $book->fill($validatedData);
            $book->save();
            $updatedBook = Book::find($id);

            return response()->json([
                'success' => 'Book updated successfully!',
                'book' => $updatedBook
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update book: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(array('success delete data : ' => $book));
    }
}
