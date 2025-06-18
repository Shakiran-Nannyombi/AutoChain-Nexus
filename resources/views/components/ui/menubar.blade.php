@props(['class' => ''])
 
<nav {{ $attributes->merge(['class' => 'flex items-center space-x-4 ' . $class]) }}>
    {{ $slot }}
</nav> 