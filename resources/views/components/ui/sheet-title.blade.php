@props(['class' => ''])

@php
    $baseClasses = 'text-lg font-semibold text-foreground';
@endphp

<h2 {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</h2> 