<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * @param Request $request Can be empty, contain a gameId to filter or an eventDateStart and eventDateEnd to filter events
     * @return \Illuminate\Http\JsonResponse A list of events that pass through all of the given filters
     */
    public function getAllEvents(Request $request): \Illuminate\Http\JsonResponse
    {
        $eventsQuery = Event::with(['game', 'participants', 'creator']);

        // Apply a filter if a gameId is provided
        if ($request->filled('gameId')) {
            $eventsQuery->where('gameId', $request->input('gameId'));
        }

        // Apply a filter if a date is provided
        if ($request->filled('eventDateStart') && $request->filled('eventDateEnd')) {
            $eventsQuery->whereBetween('eventDate', [$request->input("eventDateStart"), $request->input("eventDateEnd")]);
        }
        else {
            $eventsQuery->where('eventDate', '>=', today());
        }

        $events = $eventsQuery->get();

        // Get the current user and retrieve their joined event IDs
        $user = $request->user();
        $joinedEventIds = $user->participations()->pluck('event.id')->toArray();

        // Add "userJoined" field to each event based on user participation
        $events->each(function ($event) use ($joinedEventIds) {
            $event->userJoined = in_array($event->id, $joinedEventIds);
        });

        // Return the events with the "userJoined" field as JSON
        return response()->json($events);
    }

    public function getUserSubscribedEvents(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $events = $user->participations()->with('game', 'participants')->where('eventDate', '>=', today())->get();
        return response()->json($events);
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

    public function changeJoinedStatus(Request $request, Event $event) {
        $user = $request->user();
        $currentJoinStatus = $user->participations()->where("event.id", $event->id)->exists();

        if (!$currentJoinStatus) {
            try {
                $event->addParticipant($user);
            }
            catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        else { // Remove the participation
            $user->participations()->detach($event->id);
        }

        return response()->json(['userJoined' => !$currentJoinStatus]);
    }
}
