<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Enter the 6-digit code sent to your email, then choose a new password.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Reset Code -->
        <div class="mt-4">
            <x-input-label for="code" :value="__('Reset Code')" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required autocomplete="one-time-code" maxlength="6" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ showPassword: false }">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-12" x-bind:type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password" />
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
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4" x-data="{ showPasswordConfirmation: false }">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-12"
                                    x-bind:type="showPasswordConfirmation ? 'text' : 'password'"
                                    name="password_confirmation" required autocomplete="new-password" />
                <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation" class="absolute inset-y-0 right-0 flex items-center px-4 text-sky-600" x-bind:aria-label="showPasswordConfirmation ? 'Hide password' : 'Show password'">
                    <svg x-show="!showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.88 5.08A10.45 10.45 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a18.05 18.05 0 01-4.13 4.83" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.6 6.6A18.38 18.38 0 002.25 12s3.75 7.5 9.75 7.5c1.34 0 2.61-.2 3.78-.56" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
