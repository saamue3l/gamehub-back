<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function getUserForModification(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            return response()->json([
                'email' => $user->email,
                'username' => $user->username,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors du changement de mot de passe'
            ], 500);
        }
    }

    public function updateProfile(Request $request): JsonResponse
    {
        try {

            Log::info('Request data:', $request->all());

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            DB::beginTransaction();

            $userId = $user->id;

            $validator = Validator::make($request->all(), [
                'username' => ['sometimes', 'string', 'max:50',
                    $userId ? Rule::unique('user', 'username')->ignore($userId, 'id') : 'unique:user,username'],
                'email' => ['sometimes', 'string', 'email', 'max:100',
                    $userId ? Rule::unique('user', 'email')->ignore($userId, 'id') : 'unique:user,email'],
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }

            // Gérer l'upload de l'image
            $profilePicturePath = null;
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');

                // Créer une image depuis le fichier uploadé
                $sourceImage = imagecreatefromstring(file_get_contents($file->path()));

                // Obtenir les dimensions originales
                $width = imagesx($sourceImage);
                $height = imagesy($sourceImage);

                // Calculer les dimensions pour un carré de 300x300
                $size = 300;
                $square = imagecreatetruecolor($size, $size);

                // Garder la transparence si c'est un PNG
                if ($file->getClientMimeType() === 'image/png') {
                    imagealphablending($square, false);
                    imagesavealpha($square, true);
                }

                // Calculer le ratio pour le recadrage
                $ratio = max($size / $width, $size / $height);
                $new_width = $width * $ratio;
                $new_height = $height * $ratio;
                $x = ($size - $new_width) / 2;
                $y = ($size - $new_height) / 2;

                // Redimensionner et recadrer
                imagecopyresampled(
                    $square,
                    $sourceImage,
                    $x,
                    $y,
                    0,
                    0,
                    $new_width,
                    $new_height,
                    $width,
                    $height
                );

                // Générer un nom unique
                $fileName = Str::uuid() . '.jpg';
                $profilePicturePath = 'profiles/' . $fileName;

                // Créer un buffer temporaire
                ob_start();
                imagejpeg($square, null, 90);
                $imageData = ob_get_clean();

                // Supprimer l'ancienne image si elle existe
                if ($user->picture) {
                    Storage::disk('public')->delete($user->picture);
                }

                // Sauvegarder la nouvelle image
                Storage::disk('public')->put($profilePicturePath, $imageData);

                // Libérer la mémoire
                imagedestroy($sourceImage);
                imagedestroy($square);
            }

            // Mettre à jour l'utilisateur
            $updateData = [];

            if ($request->input('email')) {
                $updateData['email'] = $request->input('email');
            }

            if ($request->input('username')) {
                $updateData['username'] = $request->input('username');
            }

            if (isset($profilePicturePath)) {
                $updateData['picture'] = $profilePicturePath;
            }

            Log::info('Request content:', [
                'all' => $request->all(),
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'hasFile' => $request->hasFile('picture')
            ]);

            if (!empty($updateData)) {
                $user->update($updateData);
            }

            DB::commit();

            $response = ['message' => 'Profil mis à jour avec succès'];

            if ($profilePicturePath) {
                $response['picture'] = url('storage/' . $profilePicturePath);
            }
            else if ($user->picture) {
                $response['picture'] = url('storage/' . $user->picture);
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($profilePicturePath)) {
                Storage::disk('public')->delete($profilePicturePath);
            }

            Log::error('Exception during profile modification', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => "Une erreur est survenue lors de la modification du profil"
            ], 500);
        }
    }

    public function changePassword(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string|min:8',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8'
            ], [
                'current_password.required' => 'Le mot de passe actuel est requis',
                'current_password.min' => 'Le mot de passe actuel doit faire au moins 8 caractères',
                'password.required' => 'Le nouveau mot de passe est requis',
                'password.min' => 'Le nouveau mot de passe doit faire au moins 8 caractères',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
                'password_confirmation.required' => 'La confirmation du mot de passe est requise',
                'password_confirmation.min' => 'La confirmation du mot de passe doit faire au moins 8 caractères'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Vérifier que l'ancien mot de passe est correct
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Le mot de passe actuel est incorrect'
                ], 422);
            }

            // Mettre à jour le mot de passe
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'message' => 'Mot de passe modifié avec succès'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors du changement de mot de passe'
            ], 500);
        }
    }
}
