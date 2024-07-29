import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice'; // Import ToastService
import Aura from '@primevue/themes/aura'; // Import the Aura theme
import Toast from 'primevue/toast'; // Import Toast component
import { hasRole, hasPermission } from './Permissions';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue', { eager: false }));
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.mixin({ methods: { hasRole, hasPermission } });
        app.use(plugin);
        app.use(ZiggyVue);
        app.use(PrimeVue, {
            theme: {
                preset: Aura,
                options: {
                    darkModeSelector: 'false',
                }
            }
        });
        app.use(ToastService);
        app.component('Toast', Toast);
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
