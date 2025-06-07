@props(['class' => ''])

@php
    $baseClasses = 'text-sm opacity-90';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</div> 