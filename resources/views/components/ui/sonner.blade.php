@props(['class' => ''])

@php
    $baseClasses = 'toaster group';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</div> 