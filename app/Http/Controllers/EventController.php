<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getAllEvents(Request $request) {
        return Event::all()->toJson();
    }
}
