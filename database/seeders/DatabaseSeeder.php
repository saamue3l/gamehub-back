<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            NotificationTypeSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
