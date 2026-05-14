import {Link, usePage} from "@inertiajs/vue3";
import {createTextVNode, createVNode, mergeProps, unref, useSSRContext, withCtx} from "vue";
import {
    ssrInterpolate,
    ssrRenderAttr,
    ssrRenderAttrs,
    ssrRenderClass,
    ssrRenderComponent,
    ssrRenderSlot
} from "vue/server-renderer";
import {DocumentTextIcon} from "@heroicons/vue/24/outline";
//#region resources/js/Layouts/AppLayout.vue
var _sfc_main$1 = {
    __name: "AppLayout",
    __ssrInlineRender: true,
    setup(__props) {
        usePage();
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<div${ssrRenderAttrs(mergeProps({class: "min-h-screen bg-zinc-50 font-sans text-zinc-900 antialiased"}, _attrs))}><header class="sticky top-0 z-40 border-b border-zinc-200 bg-white/80 backdrop-blur-md"><div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8"><div class="flex items-center gap-8">`);
            _push(ssrRenderComponent(unref(Link), {
                class: "flex items-center gap-2 font-bold tracking-tight text-zinc-900",
                href: "/"
            }, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) {
                        _push(`<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-900 text-white"${_scopeId}>`);
                        _push(ssrRenderComponent(unref(DocumentTextIcon), {class: "h-5 w-5"}, null, _parent, _scopeId));
                        _push(`</div><span${_scopeId}>Document Nest</span>`);
                    } else return [createVNode("div", {class: "flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-900 text-white"}, [createVNode(unref(DocumentTextIcon), {class: "h-5 w-5"})]), createVNode("span", null, "Document Nest")];
                }),
                _: 1
            }, _parent));
            _push(`<nav class="hidden items-center gap-1 md:flex">`);
            _push(ssrRenderComponent(unref(Link), {
                href: "/dashboard",
                class: ["rounded-lg px-3 py-2 text-sm font-medium transition-colors", _ctx.$page.component === "Dashboard" ? "bg-zinc-100 text-zinc-900" : "text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900"]
            }, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) _push(` Dashboard `);
                    else return [createTextVNode(" Dashboard ")];
                }),
                _: 1
            }, _parent));
            _push(ssrRenderComponent(unref(Link), {
                href: "/documents",
                class: ["rounded-lg px-3 py-2 text-sm font-medium transition-colors", _ctx.$page.component.startsWith("Documents/") ? "bg-zinc-100 text-zinc-900" : "text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900"]
            }, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) _push(` Documents `);
                    else return [createTextVNode(" Documents ")];
                }),
                _: 1
            }, _parent));
            _push(`</nav></div><div class="flex items-center gap-4"><div class="hidden items-center gap-3 border-r border-zinc-200 pr-4 sm:flex"><div class="text-right"><div class="text-sm font-medium text-zinc-900">${ssrInterpolate(_ctx.$page.props.auth.user?.name)}</div><div class="text-xs text-zinc-500">${ssrInterpolate(_ctx.$page.props.auth.user?.email)}</div></div>`);
            if (_ctx.$page.props.auth.user?.avatar_url) _push(`<img${ssrRenderAttr("src", _ctx.$page.props.auth.user.avatar_url)} alt="User avatar" class="h-9 w-9 rounded-full border border-zinc-200 object-cover shadow-xs">`);
            else _push(`<div class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-600 border border-zinc-200">${ssrInterpolate(_ctx.$page.props.auth.user?.name?.charAt(0))}</div>`);
            _push(`</div>`);
            _push(ssrRenderComponent(unref(Link), {
                href: "/logout",
                method: "post",
                as: "button",
                class: "rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-700 shadow-xs transition-all hover:bg-zinc-50 hover:text-zinc-900 active:scale-95"
            }, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) _push(` Logout `);
                    else return [createTextVNode(" Logout ")];
                }),
                _: 1
            }, _parent));
            _push(`</div></div></header><main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">`);
            ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
            _push(`</main></div>`);
        };
    }
};
var _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
    const ssrContext = useSSRContext();
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AppLayout.vue");
    return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
//#endregion
//#region resources/js/Components/UI/Card.vue
var _sfc_main = {
    __name: "Card",
    __ssrInlineRender: true,
    props: {
        title: String,
        subtitle: String,
        padding: {
            type: String,
            default: "px-4 py-4"
        }
    },
    setup(__props) {
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<div${ssrRenderAttrs(mergeProps({class: "overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs"}, _attrs))}>`);
            if (_ctx.$slots.header || __props.title) {
                _push(`<div class="border-b border-zinc-200 px-4 py-3">`);
                ssrRenderSlot(_ctx.$slots, "header", {}, () => {
                    if (__props.title) _push(`<h3 class="text-base font-semibold text-zinc-900">${ssrInterpolate(__props.title)}</h3>`);
                    else _push(`<!---->`);
                    if (__props.subtitle) _push(`<p class="mt-0.5 text-sm text-zinc-500">${ssrInterpolate(__props.subtitle)}</p>`);
                    else _push(`<!---->`);
                }, _push, _parent);
                _push(`</div>`);
            } else _push(`<!---->`);
            _push(`<div class="${ssrRenderClass(["px-4 py-4", __props.padding])}">`);
            ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
            _push(`</div>`);
            if (_ctx.$slots.footer) {
                _push(`<div class="border-t border-zinc-200 bg-zinc-50/50 px-4 py-3">`);
                ssrRenderSlot(_ctx.$slots, "footer", {}, null, _push, _parent);
                _push(`</div>`);
            } else _push(`<!---->`);
            _push(`</div>`);
        };
    }
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
    const ssrContext = useSSRContext();
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Card.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main$1 as n, _sfc_main as t};

//# sourceMappingURL=Card-Cxv5bgu1.js.map
