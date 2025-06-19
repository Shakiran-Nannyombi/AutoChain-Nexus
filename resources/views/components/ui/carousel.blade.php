@props(['class' => ''])
 
<div {{ $attributes->merge(['class' => 'relative overflow-hidden ' . $class]) }}>
    {{ $slot }}
</div> 