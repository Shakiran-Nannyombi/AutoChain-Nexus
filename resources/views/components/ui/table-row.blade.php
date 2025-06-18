@props(['class' => ''])

@php
    $baseClasses = 'border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted';
@endphp

<tr {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</tr> 