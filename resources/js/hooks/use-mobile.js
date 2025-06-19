const MOBILE_BREAKPOINT = 768;

export function useIsMobile() {
    let isMobile = window.innerWidth < MOBILE_BREAKPOINT;

    const mql = window.matchMedia(`(max-width: ${MOBILE_BREAKPOINT - 1}px)`);
    
    const onChange = () => {
        isMobile = window.innerWidth < MOBILE_BREAKPOINT;
        // Dispatch a custom event that can be listened to
        window.dispatchEvent(new CustomEvent('mobileChange', { detail: { isMobile } }));
    };

    mql.addEventListener("change", onChange);

    // Return a function to clean up the event listener
    return {
        isMobile,
        cleanup: () => mql.removeEventListener("change", onChange)
    };
} 