@props([
    'name' => '',
    'class' => '',
    'options' => [],
    'selected' => null,
])

@php
    $baseClasses = 'grid gap-2';
    $radioClasses = 'aspect-square h-4 w-4 rounded-full border border-primary text-primary ring-offset-background focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $class]) }}>
    @foreach($options as $value => $label)
        <div class="flex items-center space-x-2">
            <input 
                type="radio"
                name="{{ $name }}"
                id="{{ $name }}_{{ $value }}"
                value="{{ $value }}"
                {{ $selected == $value ? 'checked' : '' }}
                class="{{ $radioClasses }}"
            >
            <label for="{{ $name }}_{{ $value }}" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                {{ $label }}
            </label>
        </div>
    @endforeach
</div> 