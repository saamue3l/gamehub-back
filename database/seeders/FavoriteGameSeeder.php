<?php

namespace Database\Seeders;

use App\Models\FavoriteGame;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoriteGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FavoriteGame::factory()->count(30)->create();
    }
}
