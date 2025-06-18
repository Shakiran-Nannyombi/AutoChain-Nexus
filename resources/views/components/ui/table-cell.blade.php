@props(['class' => ''])

@php
    $baseClasses = 'p-4 align-middle [&:has([role=checkbox])]:pr-0';
@endphp

<td {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</td> 