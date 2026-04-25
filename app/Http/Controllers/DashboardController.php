<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Carousel;
use Illuminate\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $carousels = Carousel::where('active', true)
            ->orderBy('order')
            ->limit(5)
            ->get();
        
        $query = Book::query();
        
        // Handle search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('isbn', 'LIKE', '%' . $search . '%')
                  ->orWhere('author', 'LIKE', '%' . $search . '%')
                  ->orWhere('title', 'LIKE', '%' . $search . '%');
            });
        }
        
        $books = $query->get();

        return view('dashboard', [
            'carousels' => $carousels,
            'books' => $books,
        ]);
    }
}
