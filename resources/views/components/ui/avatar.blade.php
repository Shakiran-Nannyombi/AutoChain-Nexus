@props(['src' => '', 'alt' => '', 'class' => ''])

@php
    $baseClasses = 'inline-block h-10 w-10 rounded-full object-cover';
@endphp
 
<img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }} /> 