<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ToDoController;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    $events = [];
    $issues = [];
    $todos = [];

    if (auth()->check()) {
        $user = auth()->user();

        // Google Calendar
        if ($user->hasGoogleCalendar()) {
            $events = (new GoogleCalendarService())->getTodayEvents();
        }

        // Jira
        $issues = app(App\Services\JiraService::class)->issuesAssignedToMe();

        // Todos – pull newest first, tweak as you like
        $todos = $user->todos()->latest()->get();
    }

    return view('dashboard', [
        'events' => $events,
        'issues' => $issues,
        'todos'  => $todos,
    ]);
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

// Todo
Route::post('/todos', [ToDoController::class, 'store'])->middleware('auth')->name('todo.store');
Route::delete('/todos/{todo}', [ToDoController::class, 'destroy'])->middleware('auth')->name('todo.destroy');;

require __DIR__.'/auth.php';
