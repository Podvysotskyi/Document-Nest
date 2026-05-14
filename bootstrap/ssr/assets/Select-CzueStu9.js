import {mergeProps, unref, useSSRContext} from "vue";
import {
    ssrInterpolate,
    ssrRenderAttr,
    ssrRenderAttrs,
    ssrRenderClass,
    ssrRenderComponent,
    ssrRenderList
} from "vue/server-renderer";
import {ChevronDownIcon, XMarkIcon} from "@heroicons/vue/24/outline";
//#region resources/js/Components/UI/Input.vue
var _sfc_main$1 = {
    __name: "Input",
    __ssrInlineRender: true,
    props: {
        modelValue: [String, Number],
        label: String,
        error: String,
        type: {
            type: String,
            default: "text"
        },
        placeholder: String,
        required: Boolean
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
            _push(`<div class="relative"><input${ssrRenderAttr("type", __props.type)}${ssrRenderAttr("value", __props.modelValue)}${ssrRenderAttr("placeholder", __props.placeholder)} class="${ssrRenderClass([{"border-red-500 focus:ring-red-500 focus:border-red-500": __props.error}, "w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm placeholder:text-zinc-400 focus:border-zinc-900 focus:outline-hidden focus:ring-1 focus:ring-zinc-900 disabled:bg-zinc-50 disabled:text-zinc-500 pr-9"])}">`);
            if (__props.modelValue) {
                _push(`<div class="absolute inset-y-0 right-0 flex items-center pr-2"><button type="button" class="p-0.5 text-zinc-400 hover:text-zinc-600 transition-colors" title="Clear">`);
                _push(ssrRenderComponent(unref(XMarkIcon), {class: "h-4 w-4"}, null, _parent));
                _push(`</button></div>`);
            } else _push(`<!---->`);
            _push(`</div>`);
            if (__props.error) _push(`<p class="text-xs text-red-600">${ssrInterpolate(__props.error)}</p>`);
            else _push(`<!---->`);
            _push(`</div>`);
        };
    }
};
var _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
    const ssrContext = useSSRContext();
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Input.vue");
    return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
//#endregion
//#region resources/js/Components/UI/Select.vue
var _sfc_main = {
    __name: "Select",
    __ssrInlineRender: true,
    props: {
        modelValue: [String, Number],
        label: String,
        error: String,
        options: {
            type: Array,
            required: true
        },
        placeholder: String,
        required: Boolean,
        name: String,
        showReset: {
            type: Boolean,
            default: true
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
            _push(`<div class="relative"><select${ssrRenderAttr("name", __props.name)}${ssrRenderAttr("value", __props.modelValue)} class="${ssrRenderClass([{"border-red-500 focus:ring-red-500 focus:border-red-500": __props.error}, "w-full appearance-none rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm focus:border-zinc-900 focus:outline-hidden focus:ring-1 focus:ring-zinc-900 disabled:bg-zinc-50 disabled:text-zinc-500"])}">`);
            if (__props.placeholder) _push(`<option value="" disabled selected>${ssrInterpolate(__props.placeholder)}</option>`);
            else _push(`<!---->`);
            _push(`<!--[-->`);
            ssrRenderList(__props.options, (option) => {
                _push(`<option${ssrRenderAttr("value", option.value)}>${ssrInterpolate(option.label)}</option>`);
            });
            _push(`<!--]--></select><div class="absolute inset-y-0 right-0 flex items-center pr-2 space-x-1">`);
            if (__props.modelValue && __props.showReset) {
                _push(`<button type="button" class="p-0.5 text-zinc-400 hover:text-zinc-600 transition-colors" title="Clear selection">`);
                _push(ssrRenderComponent(unref(XMarkIcon), {class: "h-4 w-4"}, null, _parent));
                _push(`</button>`);
            } else _push(`<!---->`);
            _push(`<div class="pointer-events-none text-zinc-500">`);
            _push(ssrRenderComponent(unref(ChevronDownIcon), {class: "h-4 w-4"}, null, _parent));
            _push(`</div></div></div>`);
            if (__props.error) _push(`<p class="text-xs text-red-600">${ssrInterpolate(__props.error)}</p>`);
            else _push(`<!---->`);
            _push(`</div>`);
        };
    }
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
    const ssrContext = useSSRContext();
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/UI/Select.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main$1 as n, _sfc_main as t};

//# sourceMappingURL=Select-CzueStu9.js.map
