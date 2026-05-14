import {computed, mergeProps, useSSRContext} from "vue";
import {ssrRenderAttrs, ssrRenderSlot} from "vue/server-renderer";
//#region resources/js/Components/UI/Badge.vue
var _sfc_main = {
    __name: "Badge",
    __ssrInlineRender: true,
    props: {
        variant: {
            type: String,
            default: "neutral"
        }
    },
    setup(__props) {
        const props = __props;
        const variants = {
            neutral: "bg-zinc-100 text-zinc-700 border-zinc-200",
            success: "bg-emerald-50 text-emerald-700 border-emerald-200",
            warning: "bg-amber-50 text-amber-700 border-amber-200",
            danger: "bg-red-50 text-red-700 border-red-200",
            info: "bg-blue-50 text-blue-700 border-blue-200"
        };
        const classes = computed(() => {
            return ["inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium", variants[props.variant] || variants.neutral].join(" ");
        });
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<span${ssrRenderAttrs(mergeProps({class: classes.value}, _attrs))}>`);
            ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
            _push(`</span>`);
        };
    }
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
    const ssrContext = useSSRContext();
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Badge.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main as t};

//# sourceMappingURL=Badge-DXJPokTZ.js.map
