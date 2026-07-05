@props([
    'class' => '',
])

<tr {{ $attributes->merge(['class' => $class ?: 'bg-white']) }}>
    {{ $slot }}
</tr>
