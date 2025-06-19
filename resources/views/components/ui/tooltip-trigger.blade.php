@props(['class' => ''])

<button type="button" {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</button> 