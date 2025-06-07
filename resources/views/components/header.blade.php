@props(['class' => ''])

<header {{ $attributes->merge(['class' => 'bg-white shadow ' . $class]) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <!-- Logo or branding here -->
                </div>
                <nav class="ml-6 flex space-x-8">
                    {{ $slot }}
                </nav>
            </div>
            <div class="flex items-center">
                <!-- User menu or additional controls here -->
            </div>
        </div>
    </div>
</header> 