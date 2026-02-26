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
        ]);

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
        ]);

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate.');
    }

    public function destroy(Book $book)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
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
            'due_at' => now()->addDays(14),
        ]);

        $book->decrement('available');

        return redirect()->route('books.index')->with('success', 'Buku berhasil dipinjam.');
    }
}
