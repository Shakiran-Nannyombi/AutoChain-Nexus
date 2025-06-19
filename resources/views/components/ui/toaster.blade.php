@props(['class' => ''])

@php
    $baseClasses = '';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    <x-toast-viewport>
        {{ $slot }}
    </x-toast-viewport>
</div> 