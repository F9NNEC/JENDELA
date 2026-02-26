<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CarouselController extends Controller
{
    public function index(): View
    {
        $carousels = Carousel::orderBy('order')->paginate(10);
        return view('carousels.index', compact('carousels'));
    }

    public function create(): View
    {
        return view('carousels.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image_url' => 'required|string|url',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'active' => 'boolean',
        ]);

        Carousel::create($validated);

        return redirect()->route('carousels.index')
            ->with('success', 'Carousel item created successfully');
    }

    public function edit(Carousel $carousel): View
    {
        return view('carousels.edit', compact('carousel'));
    }

    public function update(Request $request, Carousel $carousel): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image_url' => 'required|string|url',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'active' => 'boolean',
        ]);

        $carousel->update($validated);

        return redirect()->route('carousels.index')
            ->with('success', 'Carousel item updated successfully');
    }

    public function destroy(Carousel $carousel): RedirectResponse
    {
        $carousel->delete();

        return redirect()->route('carousels.index')
            ->with('success', 'Carousel item deleted successfully');
    }
}
