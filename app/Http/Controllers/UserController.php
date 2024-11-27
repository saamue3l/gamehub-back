<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function searchUsers(Request $request) {
        if (isset($request->search)) {
            $currentUserId = Auth::id();

            return User::search($request->search, function ($meilisearch, $query, $options) use ($currentUserId) {
                $options['filter'] = 'id != ' . $currentUserId;
                return $meilisearch->search($query, $options);
            })->get();
        } else {
            return response()->json([
                'message' => 'The search must include a body with "search" as key.'
            ], 400);
        }
    }

}
