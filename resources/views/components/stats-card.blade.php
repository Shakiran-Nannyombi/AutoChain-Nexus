@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-white shadow rounded-lg p-6 ' . $class]) }}>
    <h3 class="text-lg font-semibold mb-2">{{ $title }}</h3>
    <p class="text-3xl font-bold">{{ $value }}</p>
    <p class="text-sm text-gray-500">{{ $description }}</p>
</div> 