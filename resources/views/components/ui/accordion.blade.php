@props(['class' => ''])
 
<div {{ $attributes->merge(['class' => 'border rounded-md ' . $class]) }}>
    {{ $slot }}
</div> 