<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //décommenter une fois le front mis en place
        //$credentials = $request->only('email', 'password');

        //supprimer une fois le front mis en place
        $credentials = [
            'email' => 'lemuel77@example.net',
            'password' => 'password123',
        ];

        if (Auth::attempt($credentials)) {

            $user = Auth::user(); //user identifié

            $token = $user->createToken('token-name')->plainTextToken;

            // Retourner le token au front-end pour les prochaines requêtes
            return response()->json([
                'message' => 'Connexion réussie',
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            // Si les infos d'auth sont incorrectes
            return response()->json([
                'message' => 'Échec de la connexion, informations incorrectes.',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}

