<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'username' => $this->generateUniqueUsername(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'statusId' => 1, // Actif
                'roleId' => 2, // Membre
            ]);
        }

        Auth::login($user);
        $token = $user->createToken('token-name')->plainTextToken;

        // Store the token and user information in cookies
        Cookie::queue(Cookie::make('access_token', $token, 60, null, null, true, false, false, 'None'));
        Cookie::queue(Cookie::make('username', $user->username, 60, null, null, true, false, false, 'None'));
        Cookie::queue(Cookie::make('xp', $user->xp, 60, null, null, true, false, false, 'None'));
        if ($user->picture) {
            Cookie::queue(Cookie::make('profile_picture', url('storage/' . $user->picture), 60, null, null, true, false, false, 'None'));
        }

        // Redirect to the URL defined in APP_URL with the suffix /google-connection
        $redirectUrl = env('APP_URL', 'https://gamehub.matthiasg.dev/') . '/google-connection';
        return redirect()->intended($redirectUrl);
    }

    private function generateUniqueUsername()
    {
        do {
            $username = strtolower(Str::random(5)) . rand(100, 999);
        } while (User::where('username', $username)->exists());

        return $username;
    }
}
