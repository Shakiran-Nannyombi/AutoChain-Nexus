@props(['class' => ''])
 
<div {{ $attributes->merge(['class' => 'bg-white rounded-md shadow-lg p-2 ' . $class]) }}>
    {{ $slot }}
</div> 