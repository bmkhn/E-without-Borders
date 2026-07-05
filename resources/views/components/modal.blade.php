@props([
    'id' => null,
    'show' => false,
])

@php
    $modalId = $id ?? 'modal_' . str_replace('-', '_', uniqid());
@endphp

<div
    x-data="{
        open: {{ $show ? 'true' : 'false' }},
        modalId: @js($modalId)
    }"
    x-cloak
    x-show="open"
    x-on:keydown.escape.window="open = false"
    class="fixed inset-0 z-50"
    style="display: none;"
    aria-hidden="true"
>
    <!-- Backdrop -->
    <div
        class="absolute inset-0 bg-black/40"
        @click="open = false"
    ></div>

    <!-- Panel -->
    <div class="relative flex min-h-full items-center justify-center px-4">
        <div
            class="w-full max-w-lg overflow-hidden rounded-lg bg-white shadow-xl ring-1 ring-black/5"
            role="dialog"
            aria-modal="true"
            :aria-labelledby="'lbl_' + modalId"
            :aria-describedby="'desc_' + modalId"
            @click.stop
        >
            @if(trim($header ?? ''))
                <div class="border-b border-gray-200 px-5 py-3">
                    {{ $header }}
                </div>
            @endif

            <div class="px-5 py-4" :id="'desc_' + modalId">
                {{ $slot }}
            </div>

            @if(trim($footer ?? ''))
                <div class="border-t border-gray-200 px-5 py-3">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
