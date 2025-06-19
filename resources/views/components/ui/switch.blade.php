@props([
    'name' => '',
    'id' => '',
    'class' => '',
    'checked' => false,
    'value' => '1',
])

@php
    $baseClasses = 'peer inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=unchecked]:bg-input';
    $thumbClasses = 'pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform data-[state=checked]:translate-x-5 data-[state=unchecked]:translate-x-0';
@endphp

<div x-data="{ internalChecked: {{ json_encode($checked) }} }" class="flex items-center space-x-2">
    <button 
        type="button"
        role="switch"
        x-bind:aria-checked="internalChecked"
        x-on:click="internalChecked = !internalChecked"
        {{ $attributes->merge([
            'id' => $id,
            'class' => $baseClasses . ' ' . $class,
        ]) }}
        x-bind:data-state="internalChecked ? 'checked' : 'unchecked'"
    >
        <span class="{{ $thumbClasses }}" x-bind:data-state="internalChecked ? 'checked' : 'unchecked'"></span>
    </button>
    <input type="hidden" name="{{ $name }}" x-bind:value="internalChecked ? '1' : '0'">

    @if($slot->isNotEmpty())
        <label for="{{ $id }}" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
            {{ $slot }}
        </label>
    @endif
</div> 