@props(['class' => ''])

@php
    $baseClasses = 'text-sm font-semibold';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</div> 