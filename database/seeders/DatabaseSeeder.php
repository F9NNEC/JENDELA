<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
        ]);

        // contoh data buku (opsional)
        DB::table('books')->insert([
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'isbn' => '9780132350884', 'available' => 3, 'pdf_path' => 'books/sample.pdf', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'The Pragmatic Programmer', 'author' => 'Andrew Hunt', 'isbn' => '9780201616224', 'available' => 2, 'pdf_path' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // contoh peminjaman untuk user test
        $testUser = User::where('email', 'test@example.com')->first();
        $book1 = DB::table('books')->where('isbn', '9780132350884')->first();
        $book2 = DB::table('books')->where('isbn', '9780201616224')->first();

        if ($testUser && $book1) {
            DB::table('borrows')->insert([
                'user_id' => $testUser->id,
                'book_id' => $book1->id,
                'borrowed_at' => now()->subDays(5),
                'due_at' => now()->addDays(9),
                'returned_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($testUser && $book2) {
            DB::table('borrows')->insert([
                'user_id' => $testUser->id,
                'book_id' => $book2->id,
                'borrowed_at' => now()->subDays(30),
                'due_at' => now()->subDays(16),
                'returned_at' => now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
