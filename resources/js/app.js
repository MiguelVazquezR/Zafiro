import './bootstrap';
import '../css/app.css';
import '../css/fonts.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

// import Toast from "vue-toastification";
// import "vue-toastification/dist/index.css";

// Import the CSS or use your own!
import "vue-toastification/dist/index.css";

import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'

const appName = 'Ingeniería Zafiro';

// const options = {
//     // You can set your default options here
// };

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ElementPlus)
            // .use(Toast, options)
            .mount(el);
    },
    progress: {
        color: '#FFD700',
    },
});
