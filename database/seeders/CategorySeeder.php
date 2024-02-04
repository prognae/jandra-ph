<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['category_name' => 'Figurines'],
            ['category_name' => 'Lab Gowns'],
            ['category_name' => 'Masks'],
            ['category_name' => 'Medicines'],
            ['category_name' => 'Stuffed Toys'],
            ['category_name' => 'Others'],
        ]);
    }
}
