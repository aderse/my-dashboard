@php
    $googleCalendarConnected = auth()->user()->hasGoogleCalendar();
@endphp

<div class="inline-flex" id="google-calendar">

    <div>
        <div class="max-w-7xl mx-auto">
            <form action="{{ route('google.redirect') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="py-2 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-700 dark:border-red-700"
                    @if($googleCalendarConnected) disabled @endif
                    >
                    {{ $googleCalendarConnected ? 'Google Calendar Connected ✓' : 'Connect Google Calendar' }}
                </button>
            </form>
        </div>
    </div>

    @if($googleCalendarConnected)
        <div>
            <form action="{{ route('google.disconnect') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="py-2 text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-700 dark:border-red-700"
                >
                    Disconnect Google Calendar
                </button>
            </form>
        </div>
    @endif
</div>