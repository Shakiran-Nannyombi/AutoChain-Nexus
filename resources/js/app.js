import './bootstrap';

import Alpine from 'alpinejs';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
});

// Get the current user ID from a meta tag or global JS variable
const userId = document.head.querySelector('meta[name="user-id"]')?.content;

if (userId) {
    window.Echo.private(`chat.${userId}`)
        .listen('MessageSent', (e) => {
            // Optionally, update the chat window if the selected user matches sender_id
            if (window.updateChatWindow) {
                window.updateChatWindow(e);
            }
            // Show notification badge or popup
            if (window.showChatNotification) {
                window.showChatNotification(e);
            }
        });
}

window.Alpine = Alpine;

Alpine.start();

// Responsive Orders Search Bar & Dropdown for Manufacturer Orders Page

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.orders-search-form');
    if (!searchForm) return;

    // Responsive: Expand search and dropdown to full width on small screens
    function handleResponsiveOrdersSearch() {
        if (window.innerWidth <= 700) {
            searchForm.classList.add('mobile');
        } else {
            searchForm.classList.remove('mobile');
        }
    }
    handleResponsiveOrdersSearch();
    window.addEventListener('resize', handleResponsiveOrdersSearch);

    // Optional: Toggle search bar visibility on very small screens
    if (window.innerWidth <= 500) {
        let toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'orders-search-toggle';
        toggleBtn.innerHTML = '<i class="fas fa-search"></i> Filter';
        searchForm.parentNode.insertBefore(toggleBtn, searchForm);
        searchForm.style.display = 'none';
        toggleBtn.addEventListener('click', function() {
            if (searchForm.style.display === 'none') {
                searchForm.style.display = 'flex';
                toggleBtn.innerHTML = '<i class="fas fa-times"></i> Close';
            } else {
                searchForm.style.display = 'none';
                toggleBtn.innerHTML = '<i class="fas fa-search"></i> Filter';
            }
        });
        // Hide form if window is resized up
        window.addEventListener('resize', function() {
            if (window.innerWidth > 500) {
                searchForm.style.display = 'flex';
                if (toggleBtn) toggleBtn.style.display = 'none';
            } else {
                searchForm.style.display = 'none';
                if (toggleBtn) toggleBtn.style.display = 'inline-block';
            }
        });
    }
});
