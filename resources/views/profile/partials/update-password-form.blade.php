<section
    x-data="{
        currentPassword: '',
        newPassword: '',
        confirmPassword: '',
        currentPasswordValid: null,
        checkingCurrent: false,
        currentPasswordTouched: false,

        get passwordsMatch() {
            return this.newPassword.length > 0 && this.newPassword === this.confirmPassword;
        },

        get confirmDirty() {
            return this.confirmPassword.length > 0;
        },

        get canSubmit() {
            return this.currentPasswordValid === true && this.passwordsMatch && !this.checkingCurrent;
        },

        async checkCurrentPassword() {
            this.currentPasswordTouched = true;
            if (this.currentPassword.length === 0) {
                this.currentPasswordValid = null;
                return;
            }

            this.checkingCurrent = true;
            try {
                const response = await fetch('{{ route('password.check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ current_password: this.currentPassword }),
                });
                const data = await response.json();
                this.currentPasswordValid = data.valid;
            } catch {
                this.currentPasswordValid = null;
            } finally {
                this.checkingCurrent = false;
            }
        }
    }"
>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" @submit.prevent="if(canSubmit && confirm('{{ __('Are you sure you want to change your password?') }}')) $el.submit()">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="relative">
                <x-text-input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                    x-model="currentPassword"
                    @input.debounce.500ms="checkCurrentPassword()"
                    x-bind:class="currentPasswordTouched && currentPasswordValid === false ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : (currentPasswordValid === true ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : '')"
                />
                <div class="absolute right-3 top-1/2 -translate-y-1/2 mt-0.5">
                    <template x-if="checkingCurrent">
                        <svg class="h-4 w-4 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </template>
                    <template x-if="!checkingCurrent && currentPasswordTouched && currentPasswordValid === true">
                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </template>
                    <template x-if="!checkingCurrent && currentPasswordTouched && currentPasswordValid === false">
                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </template>
                </div>
            </div>
            <p x-show="currentPasswordTouched && currentPasswordValid === false" class="mt-1 text-sm text-red-600 dark:text-red-400">
                {{ __('Current password is incorrect.') }}
            </p>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- New Password --}}
        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
                x-model="newPassword"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    x-model="confirmPassword"
                    x-bind:class="confirmDirty && !passwordsMatch ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : (confirmDirty && passwordsMatch ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : '')"
                />
                <div class="absolute right-3 top-1/2 -translate-y-1/2 mt-0.5">
                    <template x-if="confirmDirty && passwordsMatch">
                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </template>
                    <template x-if="confirmDirty && !passwordsMatch">
                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </template>
                </div>
            </div>
            <p x-show="confirmDirty && !passwordsMatch" class="mt-1 text-sm text-red-600 dark:text-red-400">
                {{ __('Passwords do not match.') }}
            </p>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button
                x-bind:disabled="!canSubmit"
                x-bind:class="!canSubmit ? 'opacity-50 cursor-not-allowed' : ''"
            >{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
