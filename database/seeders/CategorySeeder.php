<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['Romance', 'Comedy', 'Fiction', 'Science Fiction', 'Fantasy', 'Mystery', 'Historical Fiction'];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
