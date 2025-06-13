<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;

class CalendarFeedController extends Controller
{
    public function __invoke(Request $request, GoogleCalendarService $cal)
    {
        return response()->json(
            $cal->getTodayEvents()
        );
    }

    public function debug(GoogleCalendarService $cal)
    {
        $events = $cal->getTodayEvents();
        dd($events);
    }
}
