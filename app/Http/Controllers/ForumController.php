<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Forum;
use App\Models\User;
use Carbon\Carbon;
use Http\Discovery\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

class ForumController extends Controller
{
    /**
     * @param Request $request Can be empty, contain a gameId to filter or an eventDateStart and eventDateEnd to filter events
     * @return \Illuminate\Http\JsonResponse A list of events that pass through all of the given filters
     */
    public function getAllForums(Request $request): \Illuminate\Http\JsonResponse
    {

        sleep(5);
        // Put in cache
        $forums = Cache::remember('forums', 60, function () {
            return Forum::all();
        });
        // Return the forums and say to the client to cache the response
        return response()->json($forums)->header('Cache-Control', 'public, max-age=3600')->setEtag(sizeof($forums));
    }
}
