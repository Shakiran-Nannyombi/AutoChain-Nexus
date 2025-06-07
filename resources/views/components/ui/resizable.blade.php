@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'relative ' . $class]) }}>
    {{ $slot }}
</div> 