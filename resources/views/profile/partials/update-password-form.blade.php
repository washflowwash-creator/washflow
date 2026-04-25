<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div x-data="{ showCurrentPassword: false }">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="relative">
                <x-text-input id="update_password_current_password" name="current_password" x-bind:type="showCurrentPassword ? 'text' : 'password'" class="mt-1 block w-full pr-12" autocomplete="current-password" />
                <button type="button" @click="showCurrentPassword = !showCurrentPassword" class="absolute inset-y-0 right-0 flex items-center px-4 text-sky-600" x-bind:aria-label="showCurrentPassword ? 'Hide password' : 'Show password'">
                    <svg x-show="!showCurrentPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="showCurrentPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.88 5.08A10.45 10.45 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a18.05 18.05 0 01-4.13 4.83" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.6 6.6A18.38 18.38 0 002.25 12s3.75 7.5 9.75 7.5c1.34 0 2.61-.2 3.78-.56" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div x-data="{ showNewPassword: false }">
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative">
                <x-text-input id="update_password_password" name="password" x-bind:type="showNewPassword ? 'text' : 'password'" class="mt-1 block w-full pr-12" autocomplete="new-password" />
                <button type="button" @click="showNewPassword = !showNewPassword" class="absolute inset-y-0 right-0 flex items-center px-4 text-sky-600" x-bind:aria-label="showNewPassword ? 'Hide password' : 'Show password'">
                    <svg x-show="!showNewPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="showNewPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.88 5.08A10.45 10.45 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a18.05 18.05 0 01-4.13 4.83" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.6 6.6A18.38 18.38 0 002.25 12s3.75 7.5 9.75 7.5c1.34 0 2.61-.2 3.78-.56" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div x-data="{ showConfirmPassword: false }">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" x-bind:type="showConfirmPassword ? 'text' : 'password'" class="mt-1 block w-full pr-12" autocomplete="new-password" />
                <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 flex items-center px-4 text-sky-600" x-bind:aria-label="showConfirmPassword ? 'Hide password' : 'Show password'">
                    <svg x-show="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.88 5.08A10.45 10.45 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a18.05 18.05 0 01-4.13 4.83" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.6 6.6A18.38 18.38 0 002.25 12s3.75 7.5 9.75 7.5c1.34 0 2.61-.2 3.78-.56" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
