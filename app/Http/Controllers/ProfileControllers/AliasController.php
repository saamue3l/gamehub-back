<?php

namespace App\Http\Controllers\ProfileControllers;

use App\Models\Username;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AliasController
{
    public function updateAlias(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        $validatedData = $request->validate([
            '*' => 'required|array',
            '*.username' => 'required|string|max:255',
            '*.platformId' => 'required|exists:platform,id',
        ]);

        $platformIds = array_column($validatedData, 'platformId');

        $existingUsernames = Username::where('userId', $user->id)->get();

        foreach ($validatedData as $usernameData) {
            $username = $existingUsernames->firstWhere('platformId', $usernameData['platformId']);

            if (!$username) {
                $username = new Username();
                $username->userId = $user->id;
                $username->platformId = $usernameData['platformId'];
            }

            $username->username = $usernameData['username'];
            $username->save();
        }

        Username::where('userId', $user->id)
            ->whereNotIn('platformId', $platformIds)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Usernames updated successfully'
        ]);
    }
}
