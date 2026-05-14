import {mergeProps, useSSRContext} from "vue";
import {ssrInterpolate, ssrRenderAttr, ssrRenderAttrs, ssrRenderClass} from "vue/server-renderer";
//#region resources/js/Components/UI/Textarea.vue
var _sfc_main = {
    __name: "Textarea",
    __ssrInlineRender: true,
    props: {
        modelValue: [String, Number],
        label: String,
        error: String,
        placeholder: String,
        required: Boolean,
        rows: {
            type: [String, Number],
            default: 4
        }
    },
    emits: ["update:modelValue"],
    setup(__props) {
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<div${ssrRenderAttrs(mergeProps({class: "space-y-1.5"}, _attrs))}>`);
            if (__props.label) {
                _push(`<label class="text-sm font-medium text-zinc-700">${ssrInterpolate(__props.label)} `);
                if (__props.required) _push(`<span class="text-red-500">*</span>`);
                else _push(`<!---->`);
                _push(`</label>`);
            } else _push(`<!---->`);
            _push(`<textarea${ssrRenderAttr("placeholder", __props.placeholder)}${ssrRenderAttr("rows", __props.rows)} class="${ssrRenderClass([{"border-red-500 focus:ring-red-500 focus:border-red-500": __props.error}, "w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm placeholder:text-zinc-400 focus:border-zinc-900 focus:outline-hidden focus:ring-1 focus:ring-zinc-900 disabled:bg-zinc-50 disabled:text-zinc-500"])}">${ssrInterpolate(__props.modelValue)}</textarea>`);
            if (__props.error) _push(`<p class="text-xs text-red-600">${ssrInterpolate(__props.error)}</p>`);
            else _push(`<!---->`);
            _push(`</div>`);
        };
    }
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
    const ssrContext = useSSRContext();
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Textarea.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main as t};

//# sourceMappingURL=Textarea-BytBwBdx.js.map
