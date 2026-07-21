<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Edit Admin Account') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-card title="Edit Admin: {{ $admin->name }}">
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

                <form method="POST" action="{{ route('admin.admins.update', $admin) }}" x-data="{ submitting: false }" @submit="submitting = true">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-4" x-data="{
                        role: '{{ $currentRole }}',
                        original: {
                            name: '{{ addslashes(old('name', $admin->name)) }}',
                            email: '{{ addslashes(old('email', $admin->email)) }}',
                            role: '{{ $currentRole }}',
                            region_id: '{{ old('region_id', $admin->region_id) }}',
                            club_id: '{{ old('club_id', $admin->club_id) }}',
                        },
                        form: {
                            name: '{{ addslashes(old('name', $admin->name)) }}',
                            email: '{{ addslashes(old('email', $admin->email)) }}',
                            role: '{{ $currentRole }}',
                            region_id: '{{ old('region_id', $admin->region_id) }}',
                            club_id: '{{ old('club_id', $admin->club_id) }}',
                        },
                        get isRegional() { return this.form.role === 'regional-admin'; },
                        get isClub() { return this.form.role === 'club-admin'; },
                        password: '',
                        get isEmailLowercase() {
                            return this.form.email === '' || this.form.email === this.form.email.toLowerCase();
                        },
                        get isPasswordValid() {
                            // Password is optional in edit; if filled, must be >= 8 chars
                            return this.password === '' || this.password.length >= 8;
                        },
                        get isDirty() {
                            return this.form.name !== this.original.name
                                || this.form.email !== this.original.email
                                || this.form.role !== this.original.role
                                || this.form.region_id !== this.original.region_id
                                || this.form.club_id !== this.original.club_id
                                || this.password !== '';
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
                                fetch('{{ route('admin.check-email') }}?email=' + encodeURIComponent(value) + '&ignore={{ $admin->id }}')
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
                            <x-input-label for="name" :value="__('Name')" />
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

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <div class="relative">
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    x-model="form.email"
                                    @input="checkEmail($el.value)"
                                    @blur="form.email = form.email.toLowerCase()"
                                    required
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
                            <p x-show="form.email.length > 0 && !isEmailLowercase" x-cloak class="mt-1 text-xs text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
                                <svg class="size-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Email should be lowercase. It will be converted on save.') }}
                            </p>
                            @error('email')
                                <x-input-error class="mt-1" :messages="[$message]" />
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <select
                                id="role"
                                name="role"
                                required
                                x-model="form.role"
                                class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                @if(!isset($isNationalAdmin) || !$isNationalAdmin)
                                    <option value="super-admin" @selected($currentRole === 'super-admin')>{{ __('Super Admin') }}</option>
                                @endif
                                <option value="national-admin" @selected($currentRole === 'national-admin')>{{ __('National Admin') }}</option>
                                <option value="regional-admin" @selected($currentRole === 'regional-admin')>{{ __('Regional Admin') }}</option>
                                <option value="club-admin" @selected($currentRole === 'club-admin')>{{ __('Club Admin') }}</option>
                            </select>
                            @error('role')
                                <x-input-error class="mt-1" :messages="[$message]" />
                            @enderror
                        </div>

                        <div x-show="isRegional" x-cloak>
                            <x-input-label for="region_id" :value="__('Region')" />
                            <select
                                id="region_id"
                                name="region_id"
                                :required="isRegional"
                                x-model="form.region_id"
                                class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">{{ __('Select region') }}</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" @selected(old('region_id', $admin->region_id) == $region->id)>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('region_id')
                                <x-input-error class="mt-1" :messages="[$message]" />
                            @enderror
                        </div>

                        <div x-show="isClub" x-cloak>
                            <x-input-label for="club_id" :value="__('Club')" />
                            <select
                                id="club_id"
                                name="club_id"
                                :required="isClub"
                                x-model="form.club_id"
                                class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">{{ __('Select club') }}</option>
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}" @selected(old('club_id', $admin->club_id) == $club->id)>
                                        {{ $club->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('club_id')
                                <x-input-error class="mt-1" :messages="[$message]" />
                            @enderror
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <x-input-label for="password" :value="__('New Password')" />
                            <input
                                id="password"
                                name="password"
                                type="password"
                                x-model="password"
                                class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="{{ __('Leave blank to keep current') }}"
                            />
                            <p x-show="password.length > 0 && !isPasswordValid" x-cloak class="mt-1 text-xs text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
                                <svg class="size-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Password must be at least 8 characters if changed.') }} <span class="text-gray-500">(<span x-text="8 - password.length"></span> {{ __('more needed') }})</span>
                            </p>
                            @error('password')
                                <x-input-error class="mt-1" :messages="[$message]" />
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                x-model="confirmPassword"
                                class="mt-1.5 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="{{ __('Leave blank to keep current') }}"
                            />
                        </div>

                        <div class="flex items-center gap-3 pt-4">
                            <button
                                type="submit"
                                :disabled="!formValid || submitting"
                                :class="!formValid || submitting
                                    ? 'inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-sm text-white opacity-50 cursor-not-allowed'
                                    : 'inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-500 dark:hover:bg-indigo-400 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                                "
                            >
                                <span x-show="!submitting">{{ __('Update Admin') }}</span>
                                <span x-show="submitting" x-cloak class="inline-flex items-center gap-2">
                                    <svg class="size-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <span>{{ __('Saving...') }}</span>
                                </span>
                            </button>

                            <a
                                href="{{ route('admin.admins.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 active:bg-gray-100 transition"
                            >
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </form>

                @if($admin->id !== auth()->id())
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                        <h3 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-3">{{ __('Danger Zone') }}</h3>
                        <x-confirm-delete-modal
                            :action="route('admin.admins.destroy', $admin)"
                            title="{{ __('Delete Admin Account') }}"
                            :message="__('This will permanently delete the admin account for :name (:email). This action cannot be undone.', ['name' => $admin->name, 'email' => $admin->email])"
                            buttonText="{{ __('Delete This Admin') }}"
                            button-class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800 hover:bg-red-100 dark:hover:bg-red-900/50"
                            type="permanent"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            {{ __('Delete') }}
                        </x-confirm-delete-modal>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
