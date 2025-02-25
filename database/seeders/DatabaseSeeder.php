<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Category;
use App\Models\Book;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        
        // Create 5 authors
        Author::factory(5)->create();

        // Create 5 categories
        Category::factory(5)->create();

        // Create 20 books
        Book::factory(20)->create();
    }
}
