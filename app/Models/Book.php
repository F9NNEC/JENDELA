<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'available',
        'cover_url',
        'pdf_path',
    ];

    public function borrows()
    {
        return $this->hasMany(\App\Models\Borrow::class);
    }
}
