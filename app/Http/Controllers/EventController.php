<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * @param Request $request Can be empty, contain a gameId to filter or an eventDateStart and eventDateEnd to filter events
     * @return \Illuminate\Http\JsonResponse A list of events that pass through all of the given filters
     */
    public function getAllEvents(Request $request) {
        $events = Event::with(['game', 'participants', 'creator']);

        // Apply a filter if a gameId is provided
        if ($request->filled('gameId')) {
            $events->where('gameId', $request->input('gameId'));
        }

        // Apply a filter if a date is provided
        if ($request->filled('eventDateStart') && $request->filled('eventDateEnd')) {
            $events->whereBetween('eventDate', [$request->input("eventDateStart"), $request->input("eventDateEnd")]);
        }

        // Execute the query and return the results as JSON
        return response()->json($events->get());
    }

    public function createEvent(Request $request) {
        Event::create([
            "name" => $request->input("name"),
            "description" => $request->input("description"),
            "maxPlayers" => $request->input("maxPlayers"),
            "eventDate" => $request->input("eventDate"),
            "creatorId" => $request->user()->id,
            "gameId" => $request->input("gameId"),
        ]);
        return response()->json([], 200);
    }
}
