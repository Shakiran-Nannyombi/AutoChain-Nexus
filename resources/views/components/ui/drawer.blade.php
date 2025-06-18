@props(['class' => ''])
 
<div {{ $attributes->merge(['class' => 'fixed inset-y-0 right-0 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out ' . $class]) }}>
    {{ $slot }}
</div> 