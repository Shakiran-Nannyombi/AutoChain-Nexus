@props(['class' => ''])
 
<div {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center ' . $class]) }}>
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
        {{ $slot }}
    </div>
</div> 