<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Place;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Place::factory()->count(100)->has(Comment::factory()->count(5))->create([
            'userID' => 1,
            'cat' => 1,
        ]);

        Place::factory()->count(100)->has(Comment::factory()->count(5))->create([
            'userID' => 1,
            'cat' => 2,
        ]);

        Place::factory()->count(100)->has(Comment::factory()->count(5))->create([
            'userID' => 1,
            'cat' => 3,
        ]);

        Place::factory()->count(100)->has(Comment::factory()->count(5))->create([
            'userID' => 1,
            'cat' => 4,
        ]);

        Place::factory()->count(100)->has(Comment::factory()->count(5))->create([
            'userID' => 2,
            'cat' => 1,
        ]);

        Place::factory()->count(100)->has(Comment::factory()->count(5))->create([
            'userID' => 2,
            'cat' => 2,
        ]);

        Place::factory()->count(100)->has(Comment::factory()->count(5))->create([
            'userID' => 2,
            'cat' => 3,
        ]);

        Place::factory()->count(100)->has(Comment::factory()->count(5))->create([
            'userID' => 2,
            'cat' => 4,
        ]);
    }
}
