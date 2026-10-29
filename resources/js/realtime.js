import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

document.addEventListener('DOMContentLoaded', () => {
    const userId = document.body.dataset.userId;

    if (!userId || !window.Echo) {
        return;
    }

    window.Echo.private(`user.${userId}`)
        .listen('.order.placed', (event) => {
            alert(`Order #${event.order_id} telah ditempatkan! Total: Rp ${event.total}`);
            console.log('Order Placed:', event);
        });
});
