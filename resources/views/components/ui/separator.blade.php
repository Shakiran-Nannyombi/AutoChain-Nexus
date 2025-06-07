@props(['class' => ''])

@php
    $baseClasses = 'shrink-0 bg-border h-[1px] w-full';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}></div> 