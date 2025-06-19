@props(['class' => ''])

@php
    $baseClasses = 'relative overflow-auto';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</div> 