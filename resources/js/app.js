import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Log Inertia errors to help debug white screens (e.g. admin/orders)
router.on('error', (event) => {
  console.error('[Inertia] Error:', event.detail?.errors ?? event.detail ?? event);
});

// Keep csrf-token meta in sync with Inertia props so admin/form submissions always have a valid token
router.on('success', (event) => {
  const token = event.detail?.page?.props?.csrf_token;
  if (token) {
    let meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) {
      meta = document.createElement('meta');
      meta.name = 'csrf-token';
      document.head.appendChild(meta);
    }
    meta.setAttribute('content', token);
  }
});

createInertiaApp({
    title: (title) => title || appName,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);
        // Always provide route so admin (and all pages) never get "route is not a function" / white screen
        const routeFn = function (name, ...params) {
            if (typeof window !== 'undefined' && typeof window.route === 'function') {
                if (arguments.length === 0) return window.route();
                return window.route(name, ...params);
            }
            if (arguments.length === 0) return { current: null };
            return '#';
        };
        if (typeof window !== 'undefined' && !window.route) {
            window.route = routeFn;
        }
        app.provide('route', routeFn);
        app.config.globalProperties.route = routeFn;
        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
