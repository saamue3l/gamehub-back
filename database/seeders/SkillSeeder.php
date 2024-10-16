<?php

namespace Database\Seeders;

use App\Models\SkillType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run()
    {
        // Insérer les trois compétences de base
        SkillType::insert([
            ['skillTypeId' => 1, 'label' => 'Beginner'],
            ['skillTypeId' => 2, 'label' => 'Intermediate'],
            ['skillTypeId' => 3, 'label' => 'Advanced'],
        ]);
    }
}
