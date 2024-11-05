<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function searchUser(Request $request) {
        if (isset($request->search)) {
            return User::search($request->search)->get();
        }
        else {
            return response()->json([
                'message' => 'The search must include a body with "search" as key.'
            ], 400);
        }
    }
}
