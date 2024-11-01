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
        Platform::insert([
            ['name' => 'steam', 'logoUrl' => '/steamIcon.png'],
            ['name' => 'playstation', 'logoUrl' => '/playstationIcon.png'],
            ['name' => 'xbox', 'logoUrl' => '/xboxIcon.png'],
            ['name' => 'ea', 'logoUrl' => '/eaIcon.png'],
            ['name' => 'riot', 'logoUrl' => '/riotIcon.png'],
            ['name' => 'switch', 'logoUrl' => '/switchIcon.png'],
            ['name' => 'ubisoft', 'logoUrl' => '/ubisoftIcon.png'],
            ['name' => 'discord', 'logoUrl' => '/discordIcon.png'],
            ['name' => 'battlenet', 'logoUrl' => '/battlenetIcon.png'],
            ['name' => 'twitch', 'logoUrl' => '/twitchIcon.png'],
        ]);
    }
}
