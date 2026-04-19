<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('title')->paginate(15);

        $userBorrowedBookIds = [];
        if (Auth::check()) {
            $userBorrowedBookIds = Auth::user()->borrows()->whereNull('returned_at')->pluck('book_id')->toArray();
        }

        return view('books.index', compact('books', 'userBorrowedBookIds'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('books.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:100|unique:books,isbn',
            'available' => 'required|integer|min:0',
            'cover_url' => 'nullable|url|max:2048',
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200', // 50MB max
        ]);

        if ($request->hasFile('pdf_file')) {
            $data['pdf_path'] = $request->file('pdf_file')->store('books', 'private');
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:100|unique:books,isbn,' . $book->id,
            'available' => 'required|integer|min:0',
            'cover_url' => 'nullable|url|max:2048',
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200',
        ]);

        if ($request->hasFile('pdf_file')) {
            // Delete old file if exists
            if ($book->pdf_path) {
                \Storage::disk('private')->delete($book->pdf_path);
            }
            $data['pdf_path'] = $request->file('pdf_file')->store('books', 'private');
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate.');
    }

    public function destroy(Book $book)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Delete PDF file if exists
        if ($book->pdf_path) {
            \Storage::disk('private')->delete($book->pdf_path);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku dihapus.');
    }

    public function borrow(Book $book)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Admin tidak dapat meminjam buku.');
        }

        if ($book->available < 1) {
            return redirect()->route('books.index')->with('error', 'Tidak ada stok buku tersedia.');
        }


        // prevent duplicate active borrow for same book
        if ($user->borrows()->where('book_id', $book->id)->whereNull('returned_at')->exists()) {
            return redirect()->route('books.index')->with('error', 'Anda sudah meminjam buku ini.');
        }

        Borrow::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'due_at' => now()->addDays(3), // 3 days to return
        ]);

        $book->decrement('available');

        return redirect()->route('books.index')->with('success', 'Buku berhasil dipinjam.');
    }

    public function read(Book $book)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has borrowed this book (active or returned)
        $hasBorrowed = $user->borrows()->where('book_id', $book->id)->exists();

        if (!$hasBorrowed) {
            abort(403, 'You have not borrowed this book.');
        }

        if (!$book->pdf_path) {
            return redirect()->back()->with('error', 'PDF file not available for this book.');
        }

        $disk = \Storage::disk('private');
        if (! $disk->exists($book->pdf_path)) {
            return redirect()->back()->with('error', 'PDF file not found on server.');
        }

        $pdfContents = $disk->get($book->pdf_path);
        $pdfBase64 = base64_encode($pdfContents);

        return view('books.read', compact('book', 'pdfBase64'));
    }

    public function servePdf(Book $book)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // Check if user has borrowed this book
        $hasBorrowed = $user->borrows()->where('book_id', $book->id)->exists();

        if (!$hasBorrowed) {
            abort(403, 'You have not borrowed this book.');
        }

        if (!$book->pdf_path) {
            abort(404, 'PDF file not found.');
        }

        // Log access
        \Log::info('PDF accessed', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'book_id' => $book->id,
            'book_title' => $book->title,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $disk = \Storage::disk('private');

        if (! $disk->exists($book->pdf_path)) {
            abort(404, 'PDF file not found.');
        }

        $path = $disk->path($book->pdf_path);
        $size = $disk->size($book->pdf_path);
        $stream = $disk->readStream($book->pdf_path);

        if (! $stream) {
            abort(500, 'Cannot read PDF stream.');
        }

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Length' => $size,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
        ]);

    }
}
