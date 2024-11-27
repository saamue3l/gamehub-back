<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token-name')->plainTextToken;

            $userData = $user->toArray();
            if ($user->picture) {
                $userData['picture'] = url('storage/' . $user->picture);
            }

            return response()->json([
                'message' => 'Connexion réussie',
                'token' => $token,
                'user' => $userData,
            ]);
        } else {
            return response()->json([
                'message' => 'Échec de la connexion, informations incorrectes.',
            ], 401);
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('token-name')->plainTextToken;
                return response()->json([
                    'message' => 'Connexion réussie',
                    'token' => $token,
                    'user' => $user,
                ]);
            } else {
                // Message d'erreur pour des identifiants incorrects
                return response()->json([
                    'message' => 'Échec de la connexion, informations incorrectes.',
                ], 401);
            }
        } catch (\Exception $e) {
            // Message d'erreur pour toute autre erreur
            return response()->json([
                'message' => 'Une erreur est survenue, veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
                return response()->json(['message' => 'Déconnexion réussie'], 200);
            }
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la déconnexion, veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}

