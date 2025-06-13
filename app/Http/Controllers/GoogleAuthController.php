<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Step A: send user to consent screen
    public function redirect(Request $request)
    {
        return Socialite::driver('google')
            ->scopes([
                'https://www.googleapis.com/auth/calendar.readonly',
            ])
            ->with([
                'access_type' => 'offline',
                'prompt'      => 'consent',
            ])
            ->redirect();
    }

    // Step B: Google sends user back here
    public function callback(Request $request)
    {
        // stateless() avoids CSRF mismatch if you’re using SPA frontends
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Persist
        $user = $request->user();
        $user->google_id             = $googleUser->id;
        $user->google_token          = $googleUser->token;
        $user->google_refresh_token  = $googleUser->refreshToken;
        $user->google_token_expires_at = Carbon::now()->addSeconds($googleUser->expiresIn);
        $user->save();

        return redirect()->intended('/dashboard')
            ->with('status', 'Google Calendar connected.');
    }

    // Step C: disconnect
    public function disconnect(Request $request)
    {
        $user = $request->user();
        $user->google_id             = null;
        $user->google_token          = null;
        $user->google_refresh_token  = null;
        $user->google_token_expires_at = null;
        $user->save();

        return redirect()->intended('/dashboard')
            ->with('status', 'Google Calendar disconnected.');
    }
}

