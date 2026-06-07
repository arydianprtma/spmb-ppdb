import '../css/app.css';
import './bootstrap';
import './echo';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'PPDB Riyadussalikin';

// Global 419 Page Expired Handling
router.on('error', (event) => {
    if (event.detail.errors?.status === 419 || event.detail.error?.status === 419) {
        // Refresh page automatically on 419
        window.location.reload();
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
    http: {
        xsrfCookieName: 'ppdb-XSRF-TOKEN',
    },
});
