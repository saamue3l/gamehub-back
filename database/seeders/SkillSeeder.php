<?php

namespace Database\Seeders;

use App\Models\SkillType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run()
    {
        SkillType::insert([
            ['id' => 1, 'label' => 'Débutant'],
            ['id' => 2, 'label' => 'Intermédiaire'],
            ['id' => 3, 'label' => 'Avancé'],
        ]);
    }
}
