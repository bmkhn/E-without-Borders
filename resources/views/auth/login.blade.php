<x-guest-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
            <p class="text-sm text-gray-400 mt-1">Sign in to manage your organization</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="px-4 py-3 bg-green-500/10 border border-green-500/20 rounded-xl text-sm text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('Email') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="size-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input id="email"
                        class="block w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-gray-500 outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all duration-200"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@example.com" />
                </div>
                @if ($errors->get('email'))
                    <ul class="mt-1.5 text-xs text-red-400 space-y-1">
                        @foreach ((array) $errors->get('email') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('Password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="size-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password"
                        class="block w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-gray-500 outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all duration-200"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••" />
                </div>
                @if ($errors->get('password'))
                    <ul class="mt-1.5 text-xs text-red-400 space-y-1">
                        @foreach ((array) $errors->get('password') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <label for="remember_me" class="flex items-center gap-2.5 cursor-pointer group">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded-lg bg-white/5 border border-white/10 text-amber-500 focus:ring-amber-500/50 focus:ring-offset-0 checked:bg-amber-500 transition-all duration-200">
                    <span class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">{{ __('Remember me') }}</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-gray-950 font-bold text-sm rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-200 active:scale-[0.98]">
                <span>Sign In</span>
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
            </button>
        </form>
    </div>
</x-guest-layout>
