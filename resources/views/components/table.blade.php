@props([
    'class' => '',
])

<div class="w-full overflow-x-auto">
    <table {{ $attributes->merge(['class' => $class ?: 'min-w-full divide-y divide-gray-200']) }}>
        {{ $slot }}
    </table>
</div>
