<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Profile') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $member = \App\Models\Member::query()
                    ->with(['position', 'club', 'certificates'])
                    ->where('slug', $slug)
                    ->firstOrFail();

                $profileUrl = route('member.profile', $member->slug);
                $qrText = $profileUrl;

                // QR code via QuickChart (no extra packages)
                $qrUrl = 'https://quickchart.io/qr?size=220&margin=10&format=png&label=&data=' . urlencode($qrText);
            @endphp

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                        <div class="flex-1">
                            <h3 class="text-2xl font-semibold text-gray-900">
                                {{ $member->name }}
                            </h3>

                            <div class="mt-4 space-y-2 text-sm text-gray-700">
                                <div>
                                    <span class="font-medium text-gray-900">{{ __('Club:') }}</span>
                                    {{ optional($member->club)->name ?? '-' }}
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">{{ __('Position:') }}</span>
                                    {{ optional($member->position)->name ?? '-' }}
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">{{ __('Contact:') }}</span>
                                    {{ $member->contact_number ?? '-' }}
                                </div>
                            </div>

                            @if($member->certificates && $member->certificates->count())
                                <div class="mt-6">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Certificates') }}</h4>

                                    <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
                                        @foreach($member->certificates as $certificate)
                                            <li>
                                                {{ $certificate->name ?? ($certificate->title ?? ('Certificate #' . $certificate->id)) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="shrink-0">
                            <div class="text-center">
                                <img
                                    src="{{ $qrUrl }}"
                                    alt="{{ __('QR code for member profile') }}"
                                    class="mx-auto w-44 h-44"
                                >
                                <div class="mt-2 text-xs text-gray-600">
                                    {{ __('Scan to open profile') }}
                                </div>

                                <div class="mt-3 text-xs break-all text-indigo-700">
                                    <a href="{{ $profileUrl }}" target="_blank" rel="noopener">
                                        {{ $profileUrl }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a
                            href="{{ route('member.directory') }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50 active:bg-gray-100"
                        >
                            {{ __('Back to Directory') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
