@props(['class' => ''])

<nav {{ $attributes->merge(['class' => 'flex items-center justify-center space-x-2 ' . $class]) }}>
    {{ $slot }}
</nav> 