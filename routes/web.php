<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CarouselController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Public routes (untuk guest dan authenticated users)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/books', [BookController::class, 'index'])->name('books.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/books/create', [BookController::class, 'create'])->middleware('admin.only')->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->middleware('admin.only')->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->middleware('admin.only')->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->middleware('admin.only')->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->middleware('admin.only')->name('books.destroy');
    Route::post('/books/{book}/borrow', [BookController::class, 'borrow'])->middleware('user.only')->name('books.borrow');

    // Carousel - admin only
    Route::middleware('admin.only')->group(function () {
        Route::get('/carousels', [CarouselController::class, 'index'])->name('carousels.index');
        Route::get('/carousels/create', [CarouselController::class, 'create'])->name('carousels.create');
        Route::post('/carousels', [CarouselController::class, 'store'])->name('carousels.store');
        Route::get('/carousels/{carousel}/edit', [CarouselController::class, 'edit'])->name('carousels.edit');
        Route::put('/carousels/{carousel}', [CarouselController::class, 'update'])->name('carousels.update');
        Route::delete('/carousels/{carousel}', [CarouselController::class, 'destroy'])->name('carousels.destroy');
    });

    // My borrows / history (user only)
    Route::get('/my-borrows', [\App\Http\Controllers\BorrowController::class, 'index'])->middleware('user.only')->name('borrows.index');
    Route::post('/borrows/{borrow}/return', [\App\Http\Controllers\BorrowController::class, 'returnBorrow'])->middleware('user.only')->name('borrows.return');
    Route::get('/books/{book}/read', [\App\Http\Controllers\BookController::class, 'read'])->middleware('user.only')->name('books.read');
    Route::get('/books/{book}/pdf', [\App\Http\Controllers\BookController::class, 'servePdf'])->middleware('user.only')->name('books.pdf');

    // Admin - all borrows
    Route::get('/admin/borrows', [\App\Http\Controllers\BorrowController::class, 'adminIndex'])->middleware('admin.only')->name('admin.borrows.index');
});
