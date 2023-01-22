<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->create([
            'id' => 1,
            'name' => 'Restaurants'
        ]);

        Category::factory()->create([
            'id' => 2,
            'name' => 'Shopping'
        ]);

        Category::factory()->create([
            'id' => 3,
            'name' => 'Coffee'
        ]);

        Category::factory()->create([
            'id' => 4,
            'name' => 'Groceries'
        ]);
    }
}
