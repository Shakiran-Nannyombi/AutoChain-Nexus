@props(['class' => ''])

<aside {{ $attributes->merge(['class' => 'bg-white shadow rounded-lg p-6 ' . $class]) }}>
    <nav class="space-y-4">
        {{ $slot }}
    </nav>
</aside> 