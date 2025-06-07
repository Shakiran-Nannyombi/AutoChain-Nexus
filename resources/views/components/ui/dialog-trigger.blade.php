@props(['class' => ''])

<button 
    type="button"
    x-on:click="show = true"
    {{ $attributes->merge(['class' => $class]) }}
>
    {{ $slot }}
</button> 