import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import axios from 'axios';

// Initialize axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Initialize hooks only once
if (!window.hooksInitialized) {
    import('./hooks/use-mobile.js').then(({ useIsMobile }) => {
        const { isMobile } = useIsMobile();
        window.isMobile = isMobile;
    });

    import('./hooks/use-toast.js').then(({ showToast }) => {
        window.showToast = showToast;
    });

    window.hooksInitialized = true;
}

// Utility functions
export function cn(...inputs) {
    return inputs.join(' ');
}

export function formatDate(date) {
    return new Date(date).toLocaleDateString();
}

export function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

export function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

export function throttle(func, limit) {
    let inThrottle;
    return function executedFunction(...args) {
        if (!inThrottle) {
            func(...args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Handle search
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = document.getElementById('search-input').value;
            if (query.trim()) {
                console.log('Searching for:', query);
            }
        });
    }

    // Handle logout
    const logoutButton = document.getElementById('logout-button');
    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
            document.getElementById('logout-form').submit();
        });
    }
});
