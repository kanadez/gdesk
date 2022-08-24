<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['title' => 'Возврат'],
            ['title' => 'Реф. выплата'],
            ['title' => 'Оплата услуг'],
        ];

        foreach($categories as $category) {
            Category::create($category);
        }
    }
}
