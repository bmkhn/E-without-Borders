<section
    x-data="{
        name: @js(old('name', $user->name)),
        email: @js(old('email', $user->email)),
        originalName: @js($user->name),
        originalEmail: @js($user->email),

        get isDirty() {
            return this.name !== this.originalName || this.email !== this.originalEmail;
        },

        emailAvailable: null,
        emailChecking: false,
        emailTimeout: null,
        checkEmail(value) {
            clearTimeout(this.emailTimeout);
            if (!value || !value.includes('@') || value.length < 5) {
                this.emailAvailable = null;
                this.emailChecking = false;
                return;
            }
            this.emailChecking = true;
            this.emailTimeout = setTimeout(() => {
                fetch('{{ route('admin.check-email') }}?email=' + encodeURIComponent(value) + '&ignore={{ $user->id }}')
                    .then(r => r.json())
                    .then(data => {
                        this.emailAvailable = data.available;
                        this.emailChecking = false;
                    })
                    .catch(() => {
                        this.emailAvailable = null;
                        this.emailChecking = false;
                    });
            }, 500);
        }
    }"
>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" @submit.prevent="if(isDirty && confirm('{{ __('Are you sure you want to update your profile information and email address?') }}')) $el.submit()">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" x-model="name" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative">
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full pr-10" x-model="email" @input="checkEmail($el.value)" required autocomplete="username" />
                <div class="absolute inset-y-0 right-4 top-1 flex items-center pointer-events-none">
                    <svg x-show="emailChecking" x-cloak class="size-4 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <svg x-show="!emailChecking && emailAvailable === true" x-cloak class="size-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg x-show="!emailChecking && emailAvailable === false" x-cloak class="size-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p x-show="!emailChecking && emailAvailable === false" x-cloak class="mt-1 text-sm text-red-600">{{ __('This email is already in use.') }}</p>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-300">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button
                x-bind:disabled="!isDirty || emailAvailable === false"
                x-bind:class="!isDirty || emailAvailable === false ? 'opacity-50 cursor-not-allowed' : ''"
            >{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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
