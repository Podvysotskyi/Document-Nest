import {t as _sfc_main$1} from "./Button-BKBm8czA.js";
import {Head} from "@inertiajs/vue3";
import {computed, createTextVNode, unref, useSSRContext, withCtx} from "vue";
import {ssrInterpolate, ssrRenderComponent} from "vue/server-renderer";
//#region resources/js/Pages/Error.vue
var _sfc_main = {
    __name: "Error",
    __ssrInlineRender: true,
    props: {status: Number},
    setup(__props) {
        const props = __props;
        const title = computed(() => {
            return {
                503: "Service Unavailable",
                500: "Server Error",
                404: "Page Not Found",
                403: "Forbidden"
            }[props.status] || "Error";
        });
        const description = computed(() => {
            return {
                503: "Sorry, we are doing some maintenance. Please check back soon.",
                500: "Whoops, something went wrong on our servers.",
                404: "Sorry, the page you are looking for could not be found.",
                403: "Sorry, you are forbidden from accessing this page."
            }[props.status] || "An unexpected error has occurred.";
        });
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<!--[-->`);
            _push(ssrRenderComponent(unref(Head), {title: title.value}, null, _parent));
            _push(`<div class="flex min-h-screen items-center justify-center bg-zinc-50 px-6 py-24 sm:py-32 lg:px-8"><div class="text-center"><p class="text-6xl font-black text-zinc-900">${ssrInterpolate(__props.status)}</p><h1 class="mt-4 text-3xl font-bold tracking-tight text-zinc-900 sm:text-5xl">${ssrInterpolate(title.value)}</h1><p class="mt-6 text-base leading-7 text-zinc-600">${ssrInterpolate(description.value)}</p><div class="mt-10 flex items-center justify-center gap-x-6">`);
            _push(ssrRenderComponent(_sfc_main$1, {
                href: "/dashboard",
                size: "lg"
            }, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) _push(` Go back home `);
                    else return [createTextVNode(" Go back home ")];
                }),
                _: 1
            }, _parent));
            _push(`<a href="mailto:support@documentnest.com" class="text-sm font-semibold text-zinc-900"> Contact support <span aria-hidden="true">→</span></a></div></div></div><!--]-->`);
        };
    }
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
    const ssrContext = useSSRContext();
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Error.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main as default};

//# sourceMappingURL=Error-Cd_HPUZ6.js.map
