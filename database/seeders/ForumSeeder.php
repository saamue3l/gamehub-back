<?php

namespace Database\Seeders;

use App\Models\Forum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Forum::insert([
            ["name" => "Recommandations"],
            ["name" => "Game dev"],
            ["name" => "18-25"],
            ["name" => "Jeux solos"],
            ["name" => "Screenshots contest"],
            ["name" => "Jeux 🔞"],
        ]);
    }
}
