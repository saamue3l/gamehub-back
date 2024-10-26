<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insérer les trois compétences de base
        Platform::insert([
            ['name' => 'PC', 'logoUrl' => '/tamher.jpg'],
            ['name' => 'Playstation', 'logoUrl' => '/tamher.jpg'],
            ['name' => 'Nintendo Switch', 'logoUrl' => '/tamher.jpg'],
        ]);
    }
}
