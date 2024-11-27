<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
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
        }
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Vérifiez que l'utilisateur est authentifié
            if ($request->user()) {
                // Révoquer le jeton utilisé pour authentifier l'utilisateur
                $request->user()->currentAccessToken()->delete();

                return response()->json(['message' => 'Déconnexion réussie'], 200);
            }

            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        } catch (\Exception $e) {
            // En cas d'exception, renvoyer une réponse d'erreur
            return response()->json(['message' => 'Erreur lors de la déconnexion', 'error' => $e->getMessage()], 500);
        }
    }
}

