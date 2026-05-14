import { createInertiaApp } from '@inertiajs/vue3'

await createInertiaApp({
    strictMode: true,
    pages: {
        path: './Pages',
        lazy: true,
    },
})
