@props(['class' => ''])

@php
    $baseClasses = 'flex items-center justify-center gap-1';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</div> 