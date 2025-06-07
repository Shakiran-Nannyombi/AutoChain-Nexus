@props(['class' => ''])

@php
    $baseClasses = 'w-full';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</div> 