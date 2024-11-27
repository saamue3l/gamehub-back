<?php

namespace App\Http\Controllers\ProfileControllers;

use App\Models\Availability;
use App\Models\ActionHistory;
use App\Services\SuccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AvailabilityController
{
    protected SuccessService $successService;

    public function __construct(SuccessService $successService)
    {
        $this->successService = $successService;
    }

    public function updateAvailability(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        $lastAction = ActionHistory::where('userId', $user->id)
            ->whereHas('action', function ($query) {
                $query->where('actionType', 'UPDATE_AVAILABILITY');
            })
            ->orderBy('actionDate', 'desc')
            ->first();

        $canGainXP = true;

        if ($lastAction && Carbon::parse($lastAction->actionDate)->diffInSeconds(now()) < 60) {
            $canGainXP = false;
        }

        $validatedData = $request->validate([
            '*' => 'required|array',
            '*.dayOfWeek' => 'required|string|max:20',
            '*.morning' => 'nullable|boolean',
            '*.afternoon' => 'nullable|boolean',
            '*.evening' => 'nullable|boolean',
            '*.night' => 'nullable|boolean',
        ]);

        $daysOfWeek = array_column($validatedData, 'dayOfWeek');

        $existingAvailabilities = Availability::where('userId', $user->id)->get();

        foreach ($validatedData as $availabilityData) {
            $availability = $existingAvailabilities->firstWhere('dayOfWeek', $availabilityData['dayOfWeek']);

            if (!$availability) {
                $availability = new Availability();
                $availability->userId = $user->id;
                $availability->dayOfWeek = $availabilityData['dayOfWeek'];
            }

            $availability->morning = $availabilityData['morning'] ?? false;
            $availability->afternoon = $availabilityData['afternoon'] ?? false;
            $availability->evening = $availabilityData['evening'] ?? false;
            $availability->night = $availabilityData['night'] ?? false;
            $availability->save();
        }

        Availability::where('userId', $user->id)
            ->whereNotIn('dayOfWeek', $daysOfWeek)
            ->delete();

        if ($canGainXP) {
            $result = $this->successService->handleAction($user, 'UPDATE_AVAILABILITY');
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
