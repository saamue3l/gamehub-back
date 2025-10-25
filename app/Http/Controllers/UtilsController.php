<?php

namespace App\Http\Controllers;

use App\Models\SkillType;


class UtilsController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllSkills(): \Illuminate\Http\JsonResponse
    {
        $skills = \App\Models\SkillType::all();

        return response()->json($skills);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPlatforms(): \Illuminate\Http\JsonResponse
    {
        $platforms = \App\Models\Platform::all();

        return response()->json($platforms);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllSuccess(): \Illuminate\Http\JsonResponse
    {
        $successes = \App\Models\Success::all();

        return response()->json($successes);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserById($id): \Illuminate\Http\JsonResponse
    {
        $user = \App\Models\User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        return response()->json($user);
    }
}
