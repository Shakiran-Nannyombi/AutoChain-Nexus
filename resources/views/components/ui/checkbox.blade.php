@props([
    'name' => '',
    'id' => '',
    'class' => '',
    'checked' => false,
    'value' => '1',
])

@php
    $baseClasses = 'peer h-4 w-4 shrink-0 rounded-sm border border-primary ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground';
@endphp

<div class="flex items-center space-x-2">
    <input 
        type="checkbox"
        {{ $attributes->merge([
            'name' => $name,
            'id' => $id,
            'class' => $baseClasses . ' ' . $class,
            'checked' => $checked,
            'value' => $value
        ]) }}
    >
    @if($slot->isNotEmpty())
        <label for="{{ $id }}" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
            {{ $slot }}
        </label>
    @endif
</div> 