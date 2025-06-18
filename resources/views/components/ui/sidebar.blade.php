@props(['class' => ''])

<aside {{ $attributes->merge(['class' => 'w-64 bg-white shadow-lg ' . $class]) }}>
    {{ $slot }}
</aside> 