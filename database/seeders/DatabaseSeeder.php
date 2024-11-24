<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ReactionType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StatusAndRoleSeeder::class,
            GameSeeder::class,
            PlatformSeeder::class,
            UserSeeder::class,
            SkillSeeder::class,
            FavoriteGameSeeder::class,
            EventSeeder::class,
            UsernameSeeder::class,
            AvailabilitySeeder::class,
            /* === FORUM === */
            ForumSeeder::class,
            TopicSeeder::class,
            PostSeeder::class,
            ReactionTypeSeeder::class,
            ReactionSeeder::class
        ]);
    }
}
