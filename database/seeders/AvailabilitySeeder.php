<?php

namespace Database\Seeders;

use App\Models\Availability;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    public function run()
    {
        Availability::factory()->count(10)->create();
    }
}
