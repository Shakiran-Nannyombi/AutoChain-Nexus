@props(['class' => ''])
 
<div {{ $attributes->merge(['class' => 'w-full h-full ' . $class]) }}>
    {{ $slot }}
</div> 