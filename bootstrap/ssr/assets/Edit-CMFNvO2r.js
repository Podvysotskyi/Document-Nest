import {n as _sfc_main$1, t as _sfc_main$2} from "./Card-Cxv5bgu1.js";
import {t as _sfc_main$3} from "./Button-BKBm8czA.js";
import {n as _sfc_main$4, t as _sfc_main$5} from "./Select-CzueStu9.js";
import {t as _sfc_main$6} from "./Textarea-BytBwBdx.js";
import {Head, useForm} from "@inertiajs/vue3";
import {createTextVNode, createVNode, toDisplayString, unref, useSSRContext, withCtx, withModifiers} from "vue";
import {ssrInterpolate, ssrRenderComponent} from "vue/server-renderer";
//#region resources/js/Pages/Documents/Edit.vue
var _sfc_main = {
    __name: "Edit",
    __ssrInlineRender: true,
    props: {
        document: Object,
        categories: Array,
        tags: Array,
        statuses: Array
    },
    setup(__props) {
        const props = __props;
        const form = useForm({
            title: props.document.title ?? "",
            category_id: props.document.category_id ?? "",
            tag_ids: props.document.tag_ids ?? [],
            issue_date: props.document.issue_date ?? "",
            expiry_date: props.document.expiry_date ?? "",
            status: props.document.status ?? "active",
            notes: props.document.notes ?? ""
        });
        const submit = () => {
            form.put(`/documents/${props.document.id}`);
        };
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<!--[-->`);
            _push(ssrRenderComponent(unref(Head), {title: "Edit Document"}, null, _parent));
            _push(ssrRenderComponent(_sfc_main$1, null, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) {
                        _push(`<div class="mx-auto max-w-3xl space-y-6"${_scopeId}><header${_scopeId}><h1 class="text-3xl font-bold tracking-tight text-zinc-900"${_scopeId}>Edit Document</h1><p class="mt-1 text-sm text-zinc-500"${_scopeId}>Update the details for &quot;${ssrInterpolate(__props.document.title)}&quot;.</p></header><form class="space-y-6"${_scopeId}>`);
                        _push(ssrRenderComponent(_sfc_main$2, {title: "General Information"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="grid gap-4 sm:grid-cols-2"${_scopeId}><div class="sm:col-span-2"${_scopeId}>`);
                                    _push(ssrRenderComponent(_sfc_main$4, {
                                        modelValue: unref(form).title,
                                        "onUpdate:modelValue": ($event) => unref(form).title = $event,
                                        label: "Title",
                                        placeholder: "e.g. Identity Card",
                                        error: unref(form).errors.title,
                                        required: ""
                                    }, null, _parent, _scopeId));
                                    _push(`</div>`);
                                    _push(ssrRenderComponent(_sfc_main$5, {
                                        modelValue: unref(form).category_id,
                                        "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                                        label: "Category",
                                        options: __props.categories.map((c) => ({
                                            value: c.id,
                                            label: c.name
                                        })),
                                        placeholder: "No category",
                                        error: unref(form).errors.category_id
                                    }, null, _parent, _scopeId));
                                    _push(ssrRenderComponent(_sfc_main$5, {
                                        modelValue: unref(form).status,
                                        "onUpdate:modelValue": ($event) => unref(form).status = $event,
                                        label: "Status",
                                        options: __props.statuses.map((s) => ({
                                            value: s,
                                            label: s.charAt(0).toUpperCase() + s.slice(1)
                                        })),
                                        error: unref(form).errors.status,
                                        required: ""
                                    }, null, _parent, _scopeId));
                                    _push(ssrRenderComponent(_sfc_main$4, {
                                        modelValue: unref(form).issue_date,
                                        "onUpdate:modelValue": ($event) => unref(form).issue_date = $event,
                                        type: "date",
                                        label: "Issue Date",
                                        error: unref(form).errors.issue_date
                                    }, null, _parent, _scopeId));
                                    _push(ssrRenderComponent(_sfc_main$4, {
                                        modelValue: unref(form).expiry_date,
                                        "onUpdate:modelValue": ($event) => unref(form).expiry_date = $event,
                                        type: "date",
                                        label: "Expiry Date",
                                        error: unref(form).errors.expiry_date
                                    }, null, _parent, _scopeId));
                                    _push(`<div class="sm:col-span-2"${_scopeId}>`);
                                    _push(ssrRenderComponent(_sfc_main$6, {
                                        modelValue: unref(form).notes,
                                        "onUpdate:modelValue": ($event) => unref(form).notes = $event,
                                        label: "Notes",
                                        placeholder: "Any additional details...",
                                        error: unref(form).errors.notes
                                    }, null, _parent, _scopeId));
                                    _push(`</div></div>`);
                                } else return [createVNode("div", {class: "grid gap-4 sm:grid-cols-2"}, [
                                    createVNode("div", {class: "sm:col-span-2"}, [createVNode(_sfc_main$4, {
                                        modelValue: unref(form).title,
                                        "onUpdate:modelValue": ($event) => unref(form).title = $event,
                                        label: "Title",
                                        placeholder: "e.g. Identity Card",
                                        error: unref(form).errors.title,
                                        required: ""
                                    }, null, 8, [
                                        "modelValue",
                                        "onUpdate:modelValue",
                                        "error"
                                    ])]),
                                    createVNode(_sfc_main$5, {
                                        modelValue: unref(form).category_id,
                                        "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                                        label: "Category",
                                        options: __props.categories.map((c) => ({
                                            value: c.id,
                                            label: c.name
                                        })),
                                        placeholder: "No category",
                                        error: unref(form).errors.category_id
                                    }, null, 8, [
                                        "modelValue",
                                        "onUpdate:modelValue",
                                        "options",
                                        "error"
                                    ]),
                                    createVNode(_sfc_main$5, {
                                        modelValue: unref(form).status,
                                        "onUpdate:modelValue": ($event) => unref(form).status = $event,
                                        label: "Status",
                                        options: __props.statuses.map((s) => ({
                                            value: s,
                                            label: s.charAt(0).toUpperCase() + s.slice(1)
                                        })),
                                        error: unref(form).errors.status,
                                        required: ""
                                    }, null, 8, [
                                        "modelValue",
                                        "onUpdate:modelValue",
                                        "options",
                                        "error"
                                    ]),
                                    createVNode(_sfc_main$4, {
                                        modelValue: unref(form).issue_date,
                                        "onUpdate:modelValue": ($event) => unref(form).issue_date = $event,
                                        type: "date",
                                        label: "Issue Date",
                                        error: unref(form).errors.issue_date
                                    }, null, 8, [
                                        "modelValue",
                                        "onUpdate:modelValue",
                                        "error"
                                    ]),
                                    createVNode(_sfc_main$4, {
                                        modelValue: unref(form).expiry_date,
                                        "onUpdate:modelValue": ($event) => unref(form).expiry_date = $event,
                                        type: "date",
                                        label: "Expiry Date",
                                        error: unref(form).errors.expiry_date
                                    }, null, 8, [
                                        "modelValue",
                                        "onUpdate:modelValue",
                                        "error"
                                    ]),
                                    createVNode("div", {class: "sm:col-span-2"}, [createVNode(_sfc_main$6, {
                                        modelValue: unref(form).notes,
                                        "onUpdate:modelValue": ($event) => unref(form).notes = $event,
                                        label: "Notes",
                                        placeholder: "Any additional details...",
                                        error: unref(form).errors.notes
                                    }, null, 8, [
                                        "modelValue",
                                        "onUpdate:modelValue",
                                        "error"
                                    ])])
                                ])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`<div class="flex items-center justify-end gap-3"${_scopeId}>`);
                        _push(ssrRenderComponent(_sfc_main$3, {
                            href: `/documents/${__props.document.id}`,
                            variant: "secondary"
                        }, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) _push(`Cancel`);
                                else return [createTextVNode("Cancel")];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(ssrRenderComponent(_sfc_main$3, {
                            type: "submit",
                            disabled: unref(form).processing
                        }, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) _push(`${ssrInterpolate(unref(form).processing ? "Saving..." : "Update Document")}`);
                                else return [createTextVNode(toDisplayString(unref(form).processing ? "Saving..." : "Update Document"), 1)];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`</div></form></div>`);
                    } else return [createVNode("div", {class: "mx-auto max-w-3xl space-y-6"}, [createVNode("header", null, [createVNode("h1", {class: "text-3xl font-bold tracking-tight text-zinc-900"}, "Edit Document"), createVNode("p", {class: "mt-1 text-sm text-zinc-500"}, "Update the details for \"" + toDisplayString(__props.document.title) + "\".", 1)]), createVNode("form", {
                        onSubmit: withModifiers(submit, ["prevent"]),
                        class: "space-y-6"
                    }, [createVNode(_sfc_main$2, {title: "General Information"}, {
                        default: withCtx(() => [createVNode("div", {class: "grid gap-4 sm:grid-cols-2"}, [
                            createVNode("div", {class: "sm:col-span-2"}, [createVNode(_sfc_main$4, {
                                modelValue: unref(form).title,
                                "onUpdate:modelValue": ($event) => unref(form).title = $event,
                                label: "Title",
                                placeholder: "e.g. Identity Card",
                                error: unref(form).errors.title,
                                required: ""
                            }, null, 8, [
                                "modelValue",
                                "onUpdate:modelValue",
                                "error"
                            ])]),
                            createVNode(_sfc_main$5, {
                                modelValue: unref(form).category_id,
                                "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                                label: "Category",
                                options: __props.categories.map((c) => ({
                                    value: c.id,
                                    label: c.name
                                })),
                                placeholder: "No category",
                                error: unref(form).errors.category_id
                            }, null, 8, [
                                "modelValue",
                                "onUpdate:modelValue",
                                "options",
                                "error"
                            ]),
                            createVNode(_sfc_main$5, {
                                modelValue: unref(form).status,
                                "onUpdate:modelValue": ($event) => unref(form).status = $event,
                                label: "Status",
                                options: __props.statuses.map((s) => ({
                                    value: s,
                                    label: s.charAt(0).toUpperCase() + s.slice(1)
                                })),
                                error: unref(form).errors.status,
                                required: ""
                            }, null, 8, [
                                "modelValue",
                                "onUpdate:modelValue",
                                "options",
                                "error"
                            ]),
                            createVNode(_sfc_main$4, {
                                modelValue: unref(form).issue_date,
                                "onUpdate:modelValue": ($event) => unref(form).issue_date = $event,
                                type: "date",
                                label: "Issue Date",
                                error: unref(form).errors.issue_date
                            }, null, 8, [
                                "modelValue",
                                "onUpdate:modelValue",
                                "error"
                            ]),
                            createVNode(_sfc_main$4, {
                                modelValue: unref(form).expiry_date,
                                "onUpdate:modelValue": ($event) => unref(form).expiry_date = $event,
                                type: "date",
                                label: "Expiry Date",
                                error: unref(form).errors.expiry_date
                            }, null, 8, [
                                "modelValue",
                                "onUpdate:modelValue",
                                "error"
                            ]),
                            createVNode("div", {class: "sm:col-span-2"}, [createVNode(_sfc_main$6, {
                                modelValue: unref(form).notes,
                                "onUpdate:modelValue": ($event) => unref(form).notes = $event,
                                label: "Notes",
                                placeholder: "Any additional details...",
                                error: unref(form).errors.notes
                            }, null, 8, [
                                "modelValue",
                                "onUpdate:modelValue",
                                "error"
                            ])])
                        ])]),
                        _: 1
                    }), createVNode("div", {class: "flex items-center justify-end gap-3"}, [createVNode(_sfc_main$3, {
                        href: `/documents/${__props.document.id}`,
                        variant: "secondary"
                    }, {
                        default: withCtx(() => [createTextVNode("Cancel")]),
                        _: 1
                    }, 8, ["href"]), createVNode(_sfc_main$3, {
                        type: "submit",
                        disabled: unref(form).processing
                    }, {
                        default: withCtx(() => [createTextVNode(toDisplayString(unref(form).processing ? "Saving..." : "Update Document"), 1)]),
                        _: 1
                    }, 8, ["disabled"])])], 32)])];
                }),
                _: 1
            }, _parent));
            _push(`<!--]-->`);
        };
    }
};
var _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
    const ssrContext = useSSRContext();
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Documents/Edit.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main as default};

//# sourceMappingURL=Edit-CMFNvO2r.js.map
