<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = \App\Models\Book::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'author_id' => \App\Models\Author::factory(),
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}