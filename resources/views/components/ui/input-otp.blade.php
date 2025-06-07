@props([
    'class' => '',
    'length' => 6,
    'name' => 'otp',
    'pattern' => '[0-9]*',
    'inputmode' => 'numeric'
])

<div {{ $attributes->merge(['class' => 'flex gap-2 ' . $class]) }}>
    @for ($i = 0; $i < $length; $i++)
        <input
            type="text"
            name="{{ $name }}[]"
            maxlength="1"
            pattern="{{ $pattern }}"
            inputmode="{{ $inputmode }}"
            class="w-12 h-12 text-center text-lg border rounded-md focus:border-primary focus:ring-1 focus:ring-primary"
            data-index="{{ $i }}"
            onkeyup="handleOTPInput(this)"
            onkeydown="handleOTPKeydown(event, this)"
        >
    @endfor
</div>

@once
    @push('scripts')
    <script>
        function handleOTPInput(input) {
            const index = parseInt(input.dataset.index);
            const value = input.value;
            
            if (value.length === 1) {
                const nextInput = input.parentElement.querySelector(`[data-index="${index + 1}"]`);
                if (nextInput) {
                    nextInput.focus();
                }
            }
        }

        function handleOTPKeydown(event, input) {
            const index = parseInt(input.dataset.index);
            
            if (event.key === 'Backspace' && !input.value) {
                const prevInput = input.parentElement.querySelector(`[data-index="${index - 1}"]`);
                if (prevInput) {
                    prevInput.focus();
                }
            }
        }
    </script>
    @endpush
@endonce 