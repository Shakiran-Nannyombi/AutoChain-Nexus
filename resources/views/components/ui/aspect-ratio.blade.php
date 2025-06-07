@props(['ratio' => '16/9', 'class' => ''])
 
<div {{ $attributes->merge(['class' => 'relative ' . $class]) }} style="padding-bottom: {{ $ratio }};">
    {{ $slot }}
</div> 