<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Buku - listing & actions
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->middleware('admin.only')->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->middleware('admin.only')->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->middleware('admin.only')->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->middleware('admin.only')->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->middleware('admin.only')->name('books.destroy');
    Route::post('/books/{book}/borrow', [BookController::class, 'borrow'])->middleware('user.only')->name('books.borrow');

    // My borrows / history (user only)
    Route::get('/my-borrows', [\App\Http\Controllers\BorrowController::class, 'index'])->middleware('user.only')->name('borrows.index');
    Route::post('/borrows/{borrow}/return', [\App\Http\Controllers\BorrowController::class, 'returnBorrow'])->middleware('user.only')->name('borrows.return');

    // Admin - all borrows
    Route::get('/admin/borrows', [\App\Http\Controllers\BorrowController::class, 'adminIndex'])->middleware('admin.only')->name('admin.borrows.index');
});
