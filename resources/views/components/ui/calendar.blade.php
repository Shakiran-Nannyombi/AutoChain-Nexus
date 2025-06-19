@props(['class' => ''])
 
<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow p-4 ' . $class]) }}>
    {{ $slot }}
</div> 