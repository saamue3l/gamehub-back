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
            ['id' => 1, 'label' => 'Beginner'],
            ['id' => 2, 'label' => 'Intermediate'],
            ['id' => 3, 'label' => 'Advanced'],
        ]);
    }
}
