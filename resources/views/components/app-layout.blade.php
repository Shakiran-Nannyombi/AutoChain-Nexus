@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'min-h-screen bg-gray-100 ' . $class]) }}>
    <x-header />
    <main class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>
</div> 