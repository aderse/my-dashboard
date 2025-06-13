<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('JIRA Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your JIRA connection information.") }}
        </p>
    </header>

    <form id="update-jira" method="post" action="{{ route('profile.jira') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="jira_email" :value="__('Email Address')" />
            <x-text-input id="jira_email" name="jira_email" type="text" class="mt-1 block w-full" :value="old('jira_email', $user->jira_email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('jira_email')" />
        </div>

        <div>
            <x-input-label for="jira_api_key" :value="__('API Key')" />
            <x-text-input id="jira_api_key" name="jira_api_key" type="text" class="mt-1 block w-full" :value="old('jira_api_key', $user->jira_api_key)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('jira_api_key')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'jira-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
