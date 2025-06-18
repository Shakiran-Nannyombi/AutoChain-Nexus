@props(['class' => ''])

@php
    $baseClasses = 'text-sm text-muted-foreground';
@endphp

<p {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</p> 