<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusAndRoleSeeder extends Seeder
{
    public function run()
    {
        // Insertion des statuts
        DB::table('status')->insert([
            ['statusId' => 1, 'label' => 'Actif'],
            ['statusId' => 2, 'label' => 'Banni'],
        ]);

        // Insertion des rôles
        DB::table('role')->insert([
            ['roleId' => 1, 'label' => 'Admin'],
            ['roleId' => 2, 'label' => 'Member'],
        ]);
    }
}
