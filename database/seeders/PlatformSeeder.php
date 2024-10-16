<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    public function run()
    {
        // Insérer les plateformes de jeu
        Platform::insert([
            ['id' => 1, 'name' => 'PC'],
            ['id' => 2, 'name' => 'Playstation'],
            ['id' => 3, 'name' => 'Xbox'],
            ['id' => 4, 'name' => 'Switch'],
        ]);
    }
}
