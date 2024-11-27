<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        User::factory()->count(10)->create();
        // Admin
        User::create([
            'username' => 'Admin',
            'email' => 'admin@gamehub.com',
            'password' => Hash::make('password123'),
            'picture' => null,
            'xp' => 100,
            'statusId' => 1,
            'roleId' => 1,
        ]);
    }
}
