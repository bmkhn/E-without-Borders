@php
    $user = auth()->user();
@endphp

<nav class="bg-white border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    @click="$dispatch('toggle-sidebar')"
                    aria-label="Toggle sidebar"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <span class="text-gray-900 font-semibold">{{ config('app.name', 'Admin') }}</span>
            </div>

            <div class="flex items-center gap-3">
                @if($user)
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ $user->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400"
                    >
                        Login
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>
