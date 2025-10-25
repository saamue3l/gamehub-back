<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusAndRoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('status')->insert([
            ['id' => 1, 'label' => 'Active'],
            ['id' => 2, 'label' => 'Banned'],
        ]);

        DB::table('role')->insert([
            ['id' => 1, 'label' => 'Admin'],
            ['id' => 2, 'label' => 'Member'],
        ]);
    }
}
