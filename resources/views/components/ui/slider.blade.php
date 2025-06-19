@props(['class' => ''])

@php
    $baseClasses = 'relative flex w-full touch-none select-none items-center';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    <div class="relative h-2 w-full grow overflow-hidden rounded-full bg-secondary">
        <div class="absolute h-full bg-primary"></div>
    </div>
    <div class="block h-5 w-5 rounded-full border-2 border-primary bg-background ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"></div>
</div> 