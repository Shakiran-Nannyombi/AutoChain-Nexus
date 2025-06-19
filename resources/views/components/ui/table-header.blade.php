@props(['class' => ''])

@php
    $baseClasses = '[&_tr]:border-b';
@endphp

<thead {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</thead> 