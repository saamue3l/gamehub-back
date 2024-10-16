<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GameSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Insertion de 10 jeux multijoueurs
        for ($i = 1; $i <= 20; $i++) {
            DB::table('game')->insert([
                'id' => $i,
                'name' => $faker->words(3, true),  // Génère un nom de jeu aléatoire
                'coverUrlId' => $faker->imageUrl(640, 480, 'games', true, 'multiplayer game'),  // Génère une URL aléatoire pour la cover
            ]);
        }
    }
}
