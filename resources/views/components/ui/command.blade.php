@props(['class' => ''])
 
<div {{ $attributes->merge(['class' => 'bg-popover text-popover-foreground rounded-md border shadow-md p-2 ' . $class]) }}>
    {{ $slot }}
</div> 