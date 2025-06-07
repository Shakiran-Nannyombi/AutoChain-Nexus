@once
    @push('scripts')
    <script type="module">
        import { useIsMobile } from '{{ Vite::asset('resources/js/hooks/use-mobile.js') }}';
        import { showToast } from '{{ Vite::asset('resources/js/hooks/use-toast.js') }}';

        // Initialize mobile detection
        const { isMobile, cleanup } = useIsMobile();
        
        // Make hooks available globally
        window.isMobile = isMobile;
        window.showToast = showToast;

        // Cleanup on page unload
        window.addEventListener('unload', cleanup);
    </script>
    @endpush
@endonce 