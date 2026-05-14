import {Link} from "@inertiajs/vue3";
import {computed, mergeProps, renderSlot, unref, useSSRContext, withCtx} from "vue";
import {ssrRenderAttrs, ssrRenderComponent, ssrRenderSlot} from "vue/server-renderer";
//#region resources/js/Components/UI/Button.vue
var _sfc_main = {
    __name: "Button",
    __ssrInlineRender: true,
    props: {
        href: String,
        as: String,
        method: String,
        type: {
            type: String,
            default: "button"
        },
        variant: {
            type: String,
            default: "primary"
        },
        size: {
            type: String,
            default: "md"
        },
        disabled: Boolean
    },
    setup(__props) {
        const props = __props;
        const variants = {
            primary: "bg-zinc-900 text-white hover:bg-zinc-800 disabled:bg-zinc-400",
            secondary: "bg-white text-zinc-900 border border-zinc-300 hover:bg-zinc-50 disabled:bg-zinc-50 disabled:text-zinc-400",
            danger: "bg-red-600 text-white hover:bg-red-700 disabled:bg-red-300",
            ghost: "text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 disabled:text-zinc-300"
        };
        const sizes = {
            sm: "px-2.5 py-1.5 text-xs",
            md: "px-3.5 py-2 text-sm",
            lg: "px-4 py-2.5 text-base"
        };
        const classes = computed(() => {
            return [
                "inline-flex items-center justify-center rounded-lg font-medium transition-colors focus:outline-hidden focus:ring-2 focus:ring-zinc-900/10 disabled:cursor-not-allowed",
                variants[props.variant],
                sizes[props.size]
            ].join(" ");
        });
        return (_ctx, _push, _parent, _attrs) => {
            if (__props.href) _push(ssrRenderComponent(unref(Link), mergeProps({
                href: __props.href,
                as: __props.as,
                method: __props.method,
                class: classes.value,
                disabled: __props.disabled
            }, _attrs), {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent, _scopeId);
                    else return [renderSlot(_ctx.$slots, "default")];
                }),
                _: 3
            }, _parent));
            else {
                _push(`<button${ssrRenderAttrs(mergeProps({
                    type: __props.type,
                    class: classes.value,
                    disabled: __props.disabled
                }, _attrs))}>`);
                ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
                _push(`</button>`);
            }
        };
    }
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
    const ssrContext = useSSRContext();
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Button.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main as t};

//# sourceMappingURL=Button-BKBm8czA.js.map
