<?php

namespace Database\Seeders;

use App\Models\Username;
use Illuminate\Database\Seeder;

class UsernameSeeder extends Seeder
{
    public function run(): void
    {
        Username::factory()->count(30)->create();
    }
}
