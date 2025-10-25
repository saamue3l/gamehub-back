<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;


class AuthController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            try {
                $request->validate([
                    'email' => 'required|email',
                    'password' => 'required|min:6'
                ]);
            } catch (ValidationException $e) {
                return response()->json([
                    'error' => true,
                    'message' => $e->errors()['email'][0] ?? $e->errors()['password'][0] ?? 'Données de validation invalides'
                ], 422);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'error' => true,
                    'message' => 'Email ou mot de passe incorrect'
                ], 402);
            }

            $user = Auth::user();

            $userData = $user->toArray();
            if ($user->picture) {
                $userData['picture'] = url('storage/' . $user->picture);
            }

            try {
                $user->tokens()->delete();
                $token = $user->createToken('auth_token')->plainTextToken;
            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => 'Impossible de créer le jeton d\'authentification'
                ], 500);
            }

            return response()->json([
                'message' => 'Connexion réussie',
                'token' => $token,
                'user' => $userData,
            ]);


        } catch (QueryException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Service temporairement indisponible'
            ], 503);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Une erreur système est survenue'
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!$request->user()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Utilisateur non authentifié'
                ], 402);
            }

            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'error' => false,
                'message' => 'Déconnexion réussie'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Erreur lors de la déconnexion'
            ], 500);
        }
    }
}
