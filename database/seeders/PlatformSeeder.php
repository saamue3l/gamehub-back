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
            ['name' => 'battlenet', 'logoUrl' => '/battlenetIcon.png'],
            ['name' => 'discord', 'logoUrl' => '/discordIcon.png'],
            ['name' => 'ea', 'logoUrl' => '/eaIcon.png'],
            ['name' => 'playstation', 'logoUrl' => '/playstationIcon.png'],
            ['name' => 'riot', 'logoUrl' => '/riotIcon.png'],
            ['name' => 'steam', 'logoUrl' => '/steamIcon.png'],
            ['name' => 'switch', 'logoUrl' => '/switchIcon.png'],
            ['name' => 'ubisoft', 'logoUrl' => '/ubisoftIcon.png'],
            ['name' => 'xbox', 'logoUrl' => '/xboxIcon.png'],
        ]);
    }
}
