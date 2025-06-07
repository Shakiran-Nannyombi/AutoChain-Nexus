@props(['class' => ''])
 
<nav {{ $attributes->merge(['class' => 'flex ' . $class]) }}>
    <ol class="flex items-center space-x-2">
        {{ $slot }}
    </ol>
</nav> 