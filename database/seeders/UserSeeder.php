<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin user
        User::factory()->create([
            'id' => 1,
            'email' => 'admin@gmail.com',
            'role' => UserRole::Admin->value,
        ]);

        // simple user
        User::factory()->create([
            'id' => 2,
            'email' => 'user@gmail.com'
        ]);

        // random simple users
        User::factory()->count(30)->create();
    }
}
