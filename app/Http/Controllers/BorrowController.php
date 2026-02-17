<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $myBorrows = Borrow::with('book')
            ->where('user_id', $user->id)
            ->whereNull('returned_at')
            ->orderByDesc('borrowed_at')
            ->get();

        $history = Borrow::with('book')
            ->where('user_id', $user->id)
            ->whereNotNull('returned_at')
            ->orderByDesc('returned_at')
            ->get();

        return view('borrows.index', compact('myBorrows', 'history'));
    }

    public function adminIndex()
    {
        $borrows = Borrow::with(['book', 'user'])
            ->orderByDesc('borrowed_at')
            ->paginate(20);

        return view('borrows.admin', compact('borrows'));
    }

    public function returnBorrow(Borrow $borrow)
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->isAdmin() && $borrow->user_id !== $user->id) {
            abort(403);
        }

        if ($borrow->returned_at) {
            return redirect()->back()->with('info', 'Sudah dikembalikan.');
        }

        $borrow->update(['returned_at' => now()]);
        $borrow->book->increment('available');

        return redirect()->route($user->isAdmin() ? 'admin.borrows.index' : 'borrows.index')->with('success', 'Buku berhasil dikembalikan.');
    }
}
