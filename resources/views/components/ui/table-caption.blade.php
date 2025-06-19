@props(['class' => ''])

@php
    $baseClasses = 'mt-4 text-sm text-muted-foreground';
@endphp

<caption {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</caption> 