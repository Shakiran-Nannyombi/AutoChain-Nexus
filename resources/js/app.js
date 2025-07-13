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
