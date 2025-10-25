<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Services\SuccessService;
use Carbon\Carbon;
use Http\Discovery\Exception;
use Illuminate\Http\Request;


class EventController extends Controller
{

    protected SuccessService $successService;

    /**
     * @param SuccessService $successService
     */
    public function __construct(SuccessService $successService)
    {
        $this->successService = $successService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllEvents(Request $request): \Illuminate\Http\JsonResponse
    {
        $eventsQuery = Event::with(['game', 'participants', 'creator']);

        if ($request->filled('gameId')) {
            $eventsQuery->where('gameId', $request->input('gameId'));
        }

        if ($request->filled('eventDateStart') && $request->filled('eventDateEnd')) {
            $eventsQuery->whereBetween('eventDate', [$request->input("eventDateStart"), $request->input("eventDateEnd")]);
        }
        else {
            $eventsQuery->where('eventDate', '>=', today());
        }

        $events = $eventsQuery->get();

        $user = $request->user();
        $joinedEventIds = $user->participations()->pluck('event.id')->toArray();

        $events->each(function ($event) use ($joinedEventIds) {
            $event->userJoined = in_array($event->id, $joinedEventIds);
        });

        return response()->json($events);
    }

    /**
     * @param Request $request
     * @param int $eventId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEvent(Request $request, int $eventId): \Illuminate\Http\JsonResponse
    {
        $event = Event::with('game', 'participants', 'creator')->where('id', $eventId)->first();
        if (!$event) {
            return response()->json(['message' => 'L\'évènement n\'existe pas'], 404);
        }
        $event->userJoined = $event->participants->contains($request->user());

        $event->participants->each(function ($participant) {
            $participant->picture = $participant->picture ? url('storage/' . $participant->picture) : null;
        });

        return response()->json($event);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserSubscribedEvents(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $events = $user->participations()->with('game', 'participants')->where('eventDate', '>=', today())->get();
        return response()->json($events);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createEvent(Request $request) {
        $newEvent = Event::create([
            "name" => $request->input("name"),
            "description" => $request->input("description"),
            "maxPlayers" => $request->input("maxPlayers"),
            "eventDate" => $request->input("eventDate"),
            "creatorId" => $request->user()->id,
            "gameId" => $request->input("gameId"),
        ]);

        $newEvent->addParticipant($request->user());

        $result = $this->successService->handleAction($request->user(), 'CREATE_EVENT');

        return response()->json([
            'xpGained' => $result['xpGained'],
            'newSuccess' => $result['newSuccess']
        ], 200);
    }

    /**
     * @param Request $request
     * @param int $eventId
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyEvent(Request $request, int $eventId): \Illuminate\Http\JsonResponse
    {
        $event = Event::find($eventId);

        if (!$event->exists) {
            return response()->json(['message' => 'L\'évènement n\'existe pas'], 404);
        }

        if ($event->creatorId !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas le créateur de l'évènement"], 403);
        }

        if ($event->eventDate < Carbon::now()) {
            return response()->json(['message' => 'Vous ne pouvez pas modifier un évènement déjà passé'], 403);
        }

        if ($event->participants()->count() > $request->input("maxPlayers")) {
            return response()->json(['message' => 'Vous ne pouvez pas réduire le nombre de participants en dessous du nombre actuel'], 403);
        }

        $event->update([
            "name" => $request->input("name"),
            "description" => $request->input("description"),
            "maxPlayers" => $request->input("maxPlayers"),
            "eventDate" => $request->input("eventDate"),
            "gameId" => $request->input("gameId"),
        ]);

        return response()->json();
    }

    /**
     * @param Request $request
     * @param int $eventId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEvent(Request $request, int $eventId): \Illuminate\Http\JsonResponse {
        $event = Event::find($eventId);

        if (!isset($event) || !$event->exists) {
            return response()->json(['message' => 'L\'évènement n\'existe pas'], 404);
        }

        if ($event->creatorId !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['message' => "Vous n'êtes pas le créateur de l'évènement"], 403);
        }

        $event->delete();
        return response()->json();
    }

    /**
     * @param Request $request
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
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

            $result = $this->successService->handleAction($user, 'JOIN_EVENT');
        }
        else { // Remove the participation
            $user->participations()->detach($event->id);

            $result = [
                'xpGained' => null,
                'newSuccess' => null
            ];
        }

        return response()->json([
            'userJoined' => !$currentJoinStatus,
            'xpGained' => $result['xpGained'],
            'newSuccess' => $result['newSuccess']
        ]);
    }
}
