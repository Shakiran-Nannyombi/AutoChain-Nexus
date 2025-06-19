@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-white shadow rounded-lg p-6 ' . $class]) }}>
    <h2 class="text-lg font-semibold mb-4">Order Placement</h2>
    <form class="space-y-4">
        {{ $slot }}
    </form>
</div> 