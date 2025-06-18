@props(['class' => ''])

@php
    $baseClasses = '[&_tr:last-child]:border-0';
@endphp

<tbody {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</tbody> 