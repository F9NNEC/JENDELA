<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Carousel;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $carousels = Carousel::where('active', true)
            ->orderBy('order')
            ->limit(5)
            ->get();
        
        $books = Book::all();

        return view('dashboard', [
            'carousels' => $carousels,
            'books' => $books,
        ]);
    }
}
