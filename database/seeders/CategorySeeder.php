<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Sıcak İçecekler'],
            ['name' => 'Soğuk İçecekler'],
            ['name' => 'Kahvaltı'],
            ['name' => 'Ana Yemekler'],
            ['name' => 'Tatlılar'],
            ['name' => 'Aperatifler'],
            ['name' => 'Alkolsüz İçecekler'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
