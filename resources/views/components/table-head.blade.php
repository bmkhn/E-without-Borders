@props([
    'class' => '',
])

<thead {{ $attributes->merge(['class' => $class ?: 'bg-gray-50']) }}>
    {{ $slot }}
</thead>
