import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from 'ziggy-js'
import { initializeTheme } from './composables/useAppearance'
import '../css/app.css'

createInertiaApp({
    resolve: (name) => resolvePageComponent(
        `./pages/${name}.vue`,
        import.meta.glob('./pages/**/*.vue'),
    ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
    },
    title: (title) => `${title} - ${import.meta.env.VITE_APP_NAME || 'Laravel'}`,
    progress: {
        color: '#4B5563',
    },
})

initializeTheme()
