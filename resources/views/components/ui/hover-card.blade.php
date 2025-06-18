@props(['class' => ''])
 
<div {{ $attributes->merge(['class' => 'bg-white rounded-md shadow-lg p-4 ' . $class]) }}>
    {{ $slot }}
</div> 