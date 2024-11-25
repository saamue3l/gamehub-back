<?php

namespace App\Http\Controllers\ProfileControllers;

use App\Models\Username;
use App\Services\SuccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AliasController
{
    protected SuccessService $successService;

    public function __construct(SuccessService $successService)
    {
        $this->successService = $successService;
    }

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

        $newRowCreated = false;

        foreach ($validatedData as $usernameData) {
            $username = $existingUsernames->firstWhere('platformId', $usernameData['platformId']);

            if (!$username) {
                $username = new Username();
                $username->userId = $user->id;
                $username->platformId = $usernameData['platformId'];
                $newRowCreated = true;
            }

            $username->username = $usernameData['username'];
            $username->save();
        }

        Username::where('userId', $user->id)
            ->whereNotIn('platformId', $platformIds)
            ->update(['username' => '']);

        if ($newRowCreated) {
            $result = $this->successService->handleAction($user, 'UPDATE_ALIAS');
        } else {
            $result = [
                'xpGained' => null,
                'newSuccess' => null
            ];
        }

        return response()->json([
            'xpGained' => $result['xpGained'],
            'newSuccess' => $result['newSuccess']
        ]);
    }
}
