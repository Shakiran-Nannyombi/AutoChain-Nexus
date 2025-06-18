@props(['class' => ''])

<button {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</button> 