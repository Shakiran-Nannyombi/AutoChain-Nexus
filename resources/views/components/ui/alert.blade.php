@props(['type' => 'info', 'class' => ''])

@php
    $typeClasses = [
        'info' => 'bg-blue-50 text-blue-800 border-blue-200',
        'success' => 'bg-green-50 text-green-800 border-green-200',
        'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
        'danger' => 'bg-red-50 text-red-800 border-red-200',
    ];
    $baseClasses = 'border-l-4 p-4 rounded-md';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . ($typeClasses[$type] ?? $typeClasses['info']) . ' ' . $class]) }}>
    {{ $slot }}
</div> 