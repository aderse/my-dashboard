<?php
namespace App\Services;

use Carbon\Carbon;
use Google\Client;

class GoogleClientFactory
{
    public static function forUser($user): Client
    {
        $client = new Client();
        $client->setAuthConfig(config('services.google'));
        $client->addScope('https://www.googleapis.com/auth/calendar.readonly');

        $client->setAccessToken([
            'access_token'  => $user->google_token,
            'refresh_token' => $user->google_refresh_token,
            'expires_in'    => $user->google_token_expires_at->diffInSeconds(now()),
            'created'       => 0,
        ]);

        if ($client->isAccessTokenExpired()) {
            $newToken = $client->fetchAccessTokenWithRefreshToken();
            $user->update([
                'google_token'          => $newToken['access_token'],
                'google_token_expires_at' => Carbon::createFromTimestamp($newToken['created'] + $newToken['expires_in']),
            ]);
        }

        return $client;
    }
}
