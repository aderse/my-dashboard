<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $now = now();
    @endphp

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8"> 
        <h3 class="border border-gray-300 p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            Here's what you need to know for today: 
            <span class="text-lg"><strong>{{ $now->format('l F jS, Y') }}</strong></span>
            - <span class="pt-1" id="current-time">{{ $now->format('g:i:s A') }}</span>
            <span style="float:right" id="pomodoro-timer" class="mt-1 pt-1 text-sm text-gray-500 dark:text-gray-400">
                <strong>Pomodoro Timer:</strong> {{ auth()->user()->pomodoro_timer }} 25 minutes
            </span>
        </h3>
    </div>

<div class="flex max-w-7xl mx-auto">
    @php
        $googleCalendarConnected = auth()->user()->hasGoogleCalendar();
    @endphp

    @if ($googleCalendarConnected && !empty($events))
        <div class="flex-2 py-6 mx-auto sm:px-6 lg:px-8">
            <h3 class="text-center text-xl mb-2">Google Calendar</h3>
            <table class="py-12 w-full" cellspacing="3" cellpadding="0">
                @foreach ($events as $event)
                    @php
                        // Pick the right field and turn it into a Carbon instance on the fly
                        $start = \Carbon\Carbon::parse($event->start->dateTime ?? $event->start->date);
                        $end   = \Carbon\Carbon::parse($event->end->dateTime   ?? $event->end->date);
                        $meeting = $event->hangoutLink ?? '';
                    @endphp
                        <tr class="border border-gray-300 mb-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <td class="p-6 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $start->format('h:i a') }} – {{ $end->format('h:i a') }}</td>
                            <td class="p-6 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $event->summary }}</td>
                            <td style="text-align: right" class="p-6 text-sm text-gray-500 dark:text-gray-400">
                                @if ($meeting != '') 
                                    <a href="{{ $meeting }}" target="_blank" class="py-2 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                                        Join Meeting
                                    </a>
                                @endif
                            </td>
                        </tr>
                @endforeach
            </table>
        </div>
    @endif
    <div class="flex-1 py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between ">
            <h3 class="mb-2 text-xl">
                ToDo:
            </h3>
            <button id="add-todo" class="relative -top-2 bg-transparent hover:bg-slate-500 text-slate-700 font-semibold hover:text-white px-4 border border-slate-500 hover:border-transparent rounded">+</button>
        </div>
        <div class="border border-gray-300 p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
            <!-- resources/views/dashboard.blade.php -->
            <ul id="todo-list" class="space-y-1">
                @if(! empty($todos)) 
                    @foreach($todos as $todo)
                        <li data-id="{{ $todo->id }}"><input type="checkbox" name="{{ $todo->id }}" id="todo-{{ $todo->id }}"  class="todo-item" /> <label>{{ $todo->title }}</label></li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>


<div>
    @if (!empty($issues))
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">    
            <h3 class="text-center text-xl mb-2">JIRA Issues:</h3>
            <table class="py-12 w-full" cellspacing="3" cellpadding="0">
                @foreach ($issues as $issue)
                    <tr class="border border-gray-300 mb-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <td class="p-6 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <a href="{{ $issue['url'] }}" target="_blank" class="text-slate-600 hover:underline">
                                {{ $issue['key'] }}
                            </a>
                        </td>
                        <td class="p-6 text-lg font-semibold">
                            <a href="{{ $issue['url'] }}" target="_blank" class="text-gray-900 hover:underline">
                                {{ $issue['summary'] }}
                            </a>
                        </td>
                        <td style="text-align: right" class="p-6 text-sm text-gray-500 dark:text-gray-400">
                                {{ $issue['status'] }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif
</div>


<script>
    // when the dashboard page is loaded, get the current time and update the clock every second
    document.addEventListener('DOMContentLoaded', () => {
        const clockElement = document.getElementById('current-time');
        if (clockElement) {
            setInterval(() => {
                const now = new Date();
                clockElement.textContent = now.toLocaleTimeString();
            }, 1000);
        }
    });
</script>
@vite(['resources/js/app.js'])
</x-app-layout>
