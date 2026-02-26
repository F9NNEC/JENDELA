<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_at',
        'returned_at',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Check if borrow is overdue (more than N days past due date)
    public function getIsOverdueAttribute(): bool
    {
        if ($this->returned_at) {
            return false;
        }

        if (! $this->due_at) {
            return false;
        }

        // overdue if current time is past due_at
        return now()->gt($this->due_at);
    }

    // Check if returned late (returned after due date + overdue days)
    public function getReturnedLateAttribute(): bool
    {
        if (! $this->returned_at || ! $this->due_at) {
            return false;
        }

        // late if returned after due_at
        return $this->returned_at->gt($this->due_at);
    }
}
