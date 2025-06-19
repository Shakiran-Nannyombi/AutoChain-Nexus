@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'overflow-y-auto scrollbar-thin scrollbar-thumb-rounded scrollbar-thumb-muted ' . $class]) }}>
    {{ $slot }}
</div> 