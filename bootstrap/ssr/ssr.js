import {createInertiaApp, Head, Link} from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import {renderToString} from "@vue/server-renderer";
import {createSSRApp, h} from "vue";
//#region resources/js/ssr.js
var renderPage = (page) => createInertiaApp({
    page,
    render: renderToString,
    resolve: (name) => {
        return (/* @__PURE__ */ Object.assign({
            "./Pages/Dashboard.vue": () => import("./assets/Dashboard--942u0xm.js"),
            "./Pages/Documents/Create.vue": () => import("./assets/Create-BjfE9_9i.js"),
            "./Pages/Documents/Edit.vue": () => import("./assets/Edit-CMFNvO2r.js"),
            "./Pages/Documents/Index.vue": () => import("./assets/Index-RLUyPoGl.js"),
            "./Pages/Documents/Show.vue": () => import("./assets/Show-CCBInRmB.js"),
            "./Pages/Error.vue": () => import("./assets/Error-Cd_HPUZ6.js")
        }))[`./Pages/${name}.vue`]();
    },
    title: (title) => title ? `${title} - Document Nest` : "Document Nest",
    setup({App, props, plugin}) {
        return createSSRApp({render: () => h(App, props)}).use(plugin).component("Link", Link).component("Head", Head);
    }
});
createServer(renderPage);
//#endregion
export {renderPage as default};

//# sourceMappingURL=ssr.js.map
