@props(['class' => ''])

@php
    $baseClasses = 'w-full caption-bottom text-sm';
@endphp

<div class="relative w-full overflow-auto">
    <table {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
        {{ $slot }}
    </table>
</div> 