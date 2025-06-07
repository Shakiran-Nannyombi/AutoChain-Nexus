@props(['value' => 0, 'max' => 100, 'class' => ''])

@php
    $percentage = max(0, min(100, ($value / $max) * 100));
@endphp
 
<div {{ $attributes->merge(['class' => 'w-full bg-muted rounded-full h-2.5 ' . $class]) }}>
    <div style="width: {{ $percentage }}%" class="bg-primary h-2.5 rounded-full transition-all duration-300"></div>
</div> 