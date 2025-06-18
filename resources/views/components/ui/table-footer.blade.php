@props(['class' => ''])

@php
    $baseClasses = 'border-t bg-muted/50 font-medium [&>tr]:last:border-b-0';
@endphp

<tfoot {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    {{ $slot }}
</tfoot> 