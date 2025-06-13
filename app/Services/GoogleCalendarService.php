<?php
namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Carbon\Carbon;

class GoogleCalendarService
{
    public function getTodayEvents(): array
    {
        $user = auth()->user();
        $client = new Client();
        $client->setAuthConfig(storage_path('oauth/google-calendar.json'));
        $client->addScope(Calendar::CALENDAR_READONLY);
        $client->setAccessToken([
            'access_token'  => $user->google_token,
            'refresh_token' => $user->google_refresh_token,
            'expires_in'    => max(0, $user->google_token_expires_at->diffInSeconds(now())),
            'created'       => 0,
        ]);
        
        if ($client->isAccessTokenExpired()) {
            $new = $client->fetchAccessTokenWithRefreshToken();
            $user->update([
                'google_token'            => $new['access_token'],
                'google_token_expires_at' => Carbon::createFromTimestamp(
                    $new['created'] + $new['expires_in']
                ),
            ]);
        }

        $service = new Calendar($client);

        $timeMin = (new \DateTimeImmutable('today',   new \DateTimeZone('America/Chicago')))->format(DATE_RFC3339);
        $timeMax = (new \DateTimeImmutable('tomorrow',new \DateTimeZone('America/Chicago')))->format(DATE_RFC3339);

        $events = $service->events->listEvents('primary', [
            'timeMin'      => $timeMin,
            'timeMax'      => $timeMax,
            'singleEvents' => true,
            'orderBy'      => 'startTime',
        ]); // :contentReference[oaicite:1]{index=1}

        return iterator_to_array($events->getItems());
    }
}
