<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:user,username',
            'email' => 'required|string|email|max:100|unique:user,email',
            'password' => 'required|string|min:6',
            'passwordConfirm' => 'required|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => "Echec de la validation des champs utilisateurs"
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'xp' => 0,
            'statusId' => 1,
            'roleId' => 2,
        ]);


        return response()->json([
            'message' => 'Inscription réussie',
            'user' => $user,
        ], 201);
    }
}
