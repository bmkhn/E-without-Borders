<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Edit Region') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card title="Update Region: {{ $region->name }}">
                @if ($errors->any())
                    <div class="mb-4">
                        <x-alert type="danger">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-alert>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.regions.update', $region) }}" x-data="{ submitting: false }" @submit="submitting = true">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4" x-data="{
                        original: {
                            name: '{{ addslashes(old('name', $region->name)) }}',
                            ra_name: '{{ addslashes(old('ra_name', optional($region->regionalAdmin)->name)) }}',
                            ra_email: '{{ addslashes(old('ra_email', optional($region->regionalAdmin)->email)) }}',
                        },
                        form: {
                            name: '{{ addslashes(old('name', $region->name)) }}',
                            ra_name: '{{ addslashes(old('ra_name', optional($region->regionalAdmin)->name)) }}',
                            ra_email: '{{ addslashes(old('ra_email', optional($region->regionalAdmin)->email)) }}',
                        },
                        ra_password: '',
                        get isDirty() {
                            return this.form.name !== this.original.name
                                || this.form.ra_name !== this.original.ra_name
                                || this.form.ra_email !== this.original.ra_email
                                || this.ra_password !== '';
                        },
                        get isEmailLowercase() {
                            return this.form.ra_email === '' || this.form.ra_email === this.form.ra_email.toLowerCase();
                        },
                        get isPasswordValid() {
                            // Password is optional in edit; if filled, must be >= 8 chars
                            return this.ra_password === '' || this.ra_password.length >= 8;
                        },
                        get formValid() {
                            return this.isDirty && this.isEmailLowercase && this.isPasswordValid && this.emailAvailable !== false;
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
                                fetch('{{ route('admin.check-email') }}?email=' + encodeURIComponent(value) + '&ignore={{ $region->regionalAdmin?->id ?? '' }}')
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
                    }">
                        <div>
                            <x-input-label for="name" :value="__('Region Name')" />
                            <input
                                id="name"
                                name="name"
                                type="text"
                                x-model="form.name"
                                required
                                class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            @error('name')
                                <x-input-error class="mt-1" :messages="[$message]" />
                            @enderror
                        </div>

                        <!-- Regional Admin Account Section -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ __('Regional Admin Account') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Leave password blank to keep the current one.') }}</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="ra_name" :value="__('Admin Name')" />
                                    <input
                                        id="ra_name"
                                        name="ra_name"
                                        type="text"
                                        x-model="form.ra_name"
                                        class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    @error('ra_name')
                                        <x-input-error class="mt-1" :messages="[$message]" />
                                    @enderror
                                </div>

                                <div>
                                    <x-input-label for="ra_email" :value="__('Admin Email')" />
                                    <div class="relative">
                                        <input
                                            id="ra_email"
                                            name="ra_email"
                                            type="email"
                                            x-model="form.ra_email"
                                            @input="checkEmail($el.value)"
                                            @blur="form.ra_email = form.ra_email.toLowerCase()"
                                            class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10"
                                        />
                                        <div class="absolute inset-y-0 right-4 top-1.5 flex items-center pointer-events-none">
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
                                    <p x-show="form.ra_email.length > 0 && !isEmailLowercase" x-cloak class="mt-1 text-xs text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
                                        <svg class="size-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ __('Email should be lowercase. It will be converted on save.') }}
                                    </p>
                                    @error('ra_email')
                                        <x-input-error class="mt-1" :messages="[$message]" />
                                    @enderror
                                </div>

                                <div>
                                    <x-input-label for="ra_password" :value="__('New Password')" />
                                    <input
                                        id="ra_password"
                                        name="ra_password"
                                        type="password"
                                        x-model="ra_password"
                                        class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="{{ __('Leave blank to keep current') }}"
                                    />
                                    <p x-show="ra_password.length > 0 && !isPasswordValid" x-cloak class="mt-1 text-xs text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
                                        <svg class="size-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ __('Password must be at least 8 characters if changed.') }} <span class="text-gray-500">(<span x-text="8 - ra_password.length"></span> {{ __('more needed') }})</span>
                                    </p>
                                    @error('ra_password')
                                        <x-input-error class="mt-1" :messages="[$message]" />
                                    @enderror
                                </div>

                                <div>
                                    <x-input-label for="ra_password_confirmation" :value="__('Confirm Password')" />
                                    <input
                                        id="ra_password_confirmation"
                                        name="ra_password_confirmation"
                                        type="password"
                                        class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button
                                type="submit"
                                :disabled="!formValid || submitting"
                                :class="!formValid || submitting
                                    ? 'inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-sm text-white opacity-50 cursor-not-allowed'
                                    : 'inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-500 dark:hover:bg-indigo-400 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                                "
                            >
                                <span x-show="!submitting">{{ __('Update Region') }}</span>
                                <span x-show="submitting" x-cloak class="inline-flex items-center gap-2">
                                    <svg class="size-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <span>{{ __('Saving...') }}</span>
                                </span>
                            </button>

                            <a
                                href="{{ route('admin.regions.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 active:bg-gray-100"
                            >
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
