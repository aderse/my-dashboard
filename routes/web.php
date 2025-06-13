<?php

use App\Http\Controllers\ProfileController;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $events = [];
    if (auth()->check() && auth()->user()->hasGoogleCalendar()) {
        $events = (new GoogleCalendarService())->getTodayEvents();
    }
    $issues = [];
    if (auth()->check()) {
        $issues = app('App\Services\JiraService')->issuesAssignedToMe();
    }
    view()->share('events', $events);
    view()->share('issues', $issues);
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/jira', [ProfileController::class, 'updateJira'])->name('profile.jira');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Debug routes
// Route::get('/calendar/debug', [CalendarFeedController::class, 'debug'])->middleware('auth');
// Route::get('/jira/debug', [App\Services\JiraService::class, 'issuesAssignedToMe'])->middleware('auth');

require __DIR__.'/auth.php';
