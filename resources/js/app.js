import '../css/app.css'

import {createInertiaApp, Head, Link} from '@inertiajs/vue3'
import {createApp, h} from 'vue'

createInertiaApp({
    title: (title) => title ? `${title} - Document Nest` : 'Document Nest',
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .component('Link', Link)
            .component('Head', Head)

        if (typeof window !== 'undefined') {
            app.mount(el)
        }

        return app
    },
})
