@props(['class' => ''])

<form {{ $attributes->merge(['class' => 'space-y-6 ' . $class]) }}>
    {{ $slot }}
</form>

@once
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        const invalidInputs = form.querySelectorAll(':invalid');
                        invalidInputs.forEach(input => {
                            input.classList.add('border-red-500');
                            const errorMessage = input.getAttribute('data-error');
                            if (errorMessage) {
                                const errorElement = document.createElement('p');
                                errorElement.className = 'text-red-500 text-sm mt-1';
                                errorElement.textContent = errorMessage;
                                input.parentNode.appendChild(errorElement);
                            }
                        });
                    }
                });
            });
        });
    </script>
    @endpush
@endonce 