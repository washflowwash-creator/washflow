<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6" x-data="{ showPassword: false }">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <div class="relative w-3/4">
                    <x-text-input
                        id="password"
                        name="password"
                        x-bind:type="showPassword ? 'text' : 'password'"
                        class="mt-1 block w-full pr-12"
                        placeholder="{{ __('Password') }}"
                    />
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center px-4 text-sky-600" x-bind:aria-label="showPassword ? 'Hide password' : 'Show password'">
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.88 5.08A10.45 10.45 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a18.05 18.05 0 01-4.13 4.83" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.6 6.6A18.38 18.38 0 002.25 12s3.75 7.5 9.75 7.5c1.34 0 2.61-.2 3.78-.56" />
                        </svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
