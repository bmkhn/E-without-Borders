@php
    $user = auth()->user();
@endphp

<aside
    x-data
    x-cloak
    class="hidden lg:block lg:w-64 lg:flex-shrink-0"
>
    <div class="flex h-full flex-col border-r border-gray-200 bg-white">
        <div class="flex items-center justify-between px-4 py-4">
            <div class="text-sm font-semibold text-gray-900">Navigation</div>
        </div>

        <nav class="flex-1 px-2 pb-4">
            <ul class="space-y-1">
                <li>
                    <a
                        href="#"
                        class="group flex items-center rounded-md px-2 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900"
                    >
                        Dashboard
                    </a>
                </li>

                <li>
                    <a
                        href="#"
                        class="group flex items-center rounded-md px-2 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900"
                    >
                        Clubs
                    </a>
                </li>

                <li>
                    <a
                        href="#"
                        class="group flex items-center rounded-md px-2 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900"
                    >
                        Members
                    </a>
                </li>

                <li>
                    <a
                        href="#"
                        class="group flex items-center rounded-md px-2 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900"
                    >
                        Certificates
                    </a>
                </li>
            </ul>
        </nav>

        <div class="px-4 py-4 text-xs text-gray-500">
            @if($user)
                Signed in as <span class="font-medium text-gray-700">{{ $user->name }}</span>
            @else
                Not signed in
            @endif
        </div>
    </div>
</aside>

<!-- Mobile sidebar (toggle target) -->
<div
    x-data
    x-cloak
    x-show="false"
></div>
