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
            ['platformId' => 1, 'name' => 'PC'],
            ['platformId' => 2, 'name' => 'Playstation'],
            ['platformId' => 3, 'name' => 'Xbox'],
            ['platformId' => 4, 'name' => 'Switch'],
        ]);
    }
}
