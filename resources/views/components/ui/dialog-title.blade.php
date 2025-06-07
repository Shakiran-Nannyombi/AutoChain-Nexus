@props(['class' => ''])

@php
    $baseClasses = 'text-lg font-semibold leading-none tracking-tight';
@endphp

<h2 {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</h2> 