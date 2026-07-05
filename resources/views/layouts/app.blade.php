<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Admin') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased bg-gray-100">
        <div
            class="min-h-screen"
            x-data="{
                sidebarOpen: false
            }"
            x-init="
                window.addEventListener('toggle-sidebar', () => {
                    sidebarOpen = !sidebarOpen
                })
            "
        >
            <div class="flex min-h-screen">
                <!-- Sidebar (Desktop) -->
                @include('layouts.sidebar')

                <!-- Mobile sidebar overlay -->
                <div
                    class="fixed inset-0 z-40 lg:hidden"
                    x-show="sidebarOpen"
                    x-cloak
                    x-transition.opacity
                >
                    <div class="absolute inset-0 bg-black/30" @click="sidebarOpen = false"></div>

                    <div class="relative h-full w-72 bg-white shadow-xl">
                        @include('layouts.sidebar')
                    </div>
                </div>

                <!-- Content column -->
                <div class="flex flex-1 flex-col">
                    @include('layouts.navbar')

                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="flex-1">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        <script>
            // Alpine-friendly event dispatcher for the navbar button.
            // (Used to avoid route/controller modifications.)
            window.addEventListener('toggle-sidebar', () => {});
            document.addEventListener('DOMContentLoaded', () => {});
        </script>
    </body>
</html>
