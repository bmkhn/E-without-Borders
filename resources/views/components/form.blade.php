@props([
    'action' => null,
    'method' => 'post', // get|post|put|patch|delete
    'model' => null,
    'class' => '',
])

@php
    $resolvedAction = $action;

    if ($model && !$resolvedAction) {
        // Uses route-model binding if your app supports it; otherwise, pass action explicitly.
        $resolvedAction = route(class_basename($model) . '.update', $model);
    }

    $methodLower = strtolower($method);
@endphp

<form
    method="POST"
    action="{{ $resolvedAction }}"
    {{ $attributes->merge(['class' => $class ?: '']) }}
>
    @csrf

    @if(in_array($methodLower, ['put','patch','delete'], true))
        @method($methodLower)
    @endif

    {{ $slot }}
</form>
