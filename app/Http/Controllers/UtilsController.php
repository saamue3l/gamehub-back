<?php

namespace App\Http\Controllers;

use App\Models\SkillType;

class UtilsController extends Controller
{
    public function getAllSkills(): \Illuminate\Http\JsonResponse
    {
        $skills = \App\Models\SkillType::all();

        return response()->json($skills);
    }

    public function getAllPlatforms(): \Illuminate\Http\JsonResponse
    {
        $platforms = \App\Models\Platform::all();

        return response()->json($platforms);
    }
}
