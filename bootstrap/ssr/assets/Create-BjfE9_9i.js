import {n as _sfc_main$1, t as _sfc_main$2} from "./Card-Cxv5bgu1.js";
import {t as _sfc_main$3} from "./Button-BKBm8czA.js";
import {n as _sfc_main$4, t as _sfc_main$5} from "./Select-CzueStu9.js";
import {t as _sfc_main$6} from "./Textarea-BytBwBdx.js";
import {Head, useForm} from "@inertiajs/vue3";
import {
    createBlock,
    createCommentVNode,
    createTextVNode,
    createVNode,
    openBlock,
    toDisplayString,
    unref,
    useSSRContext,
    withCtx,
    withModifiers
} from "vue";
import {ssrInterpolate, ssrRenderComponent} from "vue/server-renderer";
import {CloudArrowUpIcon, XMarkIcon} from "@heroicons/vue/24/outline";
//#region resources/js/Pages/Documents/Create.vue
var _sfc_main = {
    __name: "Create",
    __ssrInlineRender: true,
    props: {
        categories: Array,
        tags: Array,
        statuses: Array
    },
    setup(__props) {
        const form = useForm({
            file: null,
            title: "",
            category_id: "",
            tag_ids: [],
            issue_date: "",
            expiry_date: "",
            status: "active",
            notes: ""
        });
        const submit = () => {
            form.post("/documents");
        };
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<!--[-->`);
            _push(ssrRenderComponent(unref(Head), {title: "Upload Document"}, null, _parent));
            _push(ssrRenderComponent(_sfc_main$1, null, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) {
                        _push(`<div class="mx-auto max-w-3xl space-y-6"${_scopeId}><header${_scopeId}><h1 class="text-3xl font-bold tracking-tight text-zinc-900"${_scopeId}>Upload Document</h1><p class="mt-1 text-sm text-zinc-500"${_scopeId}>Add a new document to your library. PDF, JPG, PNG, and WebP are supported.</p></header><form class="space-y-6"${_scopeId}>`);
                        _push(ssrRenderComponent(_sfc_main$2, {title: "Document File"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="space-y-4"${_scopeId}><div class="flex items-center justify-center rounded-lg border-2 border-dashed border-zinc-200 bg-zinc-50/50 p-6 transition-colors hover:border-zinc-300"${_scopeId}><div class="text-center"${_scopeId}>`);
                                    _push(ssrRenderComponent(unref(CloudArrowUpIcon), {class: "mx-auto h-10 w-10 text-zinc-400"}, null, _parent, _scopeId));
                                    _push(`<div class="mt-4 flex text-sm text-zinc-600"${_scopeId}><label class="relative cursor-pointer rounded-md font-semibold text-zinc-900 hover:text-zinc-700"${_scopeId}><span${_scopeId}>Select a file</span><input type="file" accept=".pdf,.jpg,.jpeg,.png,.webp" class="sr-only"${_scopeId}></label><p class="pl-1"${_scopeId}>or drag and drop</p></div><p class="text-xs text-zinc-500"${_scopeId}>PDF, PNG, JPG up to 10MB</p></div></div>`);
                                    if (unref(form).file) {
                                        _push(`<div class="flex items-center justify-between rounded-lg bg-zinc-100 px-4 py-2 text-sm text-zinc-700"${_scopeId}><span class="truncate font-medium"${_scopeId}>${ssrInterpolate(unref(form).file.name)}</span><button type="button" class="text-zinc-400 hover:text-zinc-600"${_scopeId}>`);
                                        _push(ssrRenderComponent(unref(XMarkIcon), {class: "h-5 w-5"}, null, _parent, _scopeId));
                                        _push(`</button></div>`);
                                    } else _push(`<!---->`);
                                    if (unref(form).errors.file) _push(`<p class="text-sm text-red-600"${_scopeId}>${ssrInterpolate(unref(form).errors.file)}</p>`);
                                    else _push(`<!---->`);
                                    _push(`</div>`);
                                } else return [createVNode("div", {class: "space-y-4"}, [
                                    createVNode("div", {class: "flex items-center justify-center rounded-lg border-2 border-dashed border-zinc-200 bg-zinc-50/50 p-6 transition-colors hover:border-zinc-300"}, [createVNode("div", {class: "text-center"}, [
                                        createVNode(unref(CloudArrowUpIcon), {class: "mx-auto h-10 w-10 text-zinc-400"}),
                                        createVNode("div", {class: "mt-4 flex text-sm text-zinc-600"}, [createVNode("label", {class: "relative cursor-pointer rounded-md font-semibold text-zinc-900 hover:text-zinc-700"}, [createVNode("span", null, "Select a file"), createVNode("input", {
                                            type: "file",
                                            accept: ".pdf,.jpg,.jpeg,.png,.webp",
                                            onInput: ($event) => unref(form).file = $event.target.files[0],
                                            class: "sr-only"
                                        }, null, 40, ["onInput"])]), createVNode("p", {class: "pl-1"}, "or drag and drop")]),
                                        createVNode("p", {class: "text-xs text-zinc-500"}, "PDF, PNG, JPG up to 10MB")
                                    ])]),
                                    unref(form).file ? (openBlock(), createBlock("div", {
                                        key: 0,
                                        class: "flex items-center justify-between rounded-lg bg-zinc-100 px-4 py-2 text-sm text-zinc-700"
                                    }, [createVNode("span", {class: "truncate font-medium"}, toDisplayString(unref(form).file.name), 1), createVNode("button", {
                                        type: "button",
                                        onClick: ($event) => unref(form).file = null,
                                        class: "text-zinc-400 hover:text-zinc-600"
                                    }, [createVNode(unref(XMarkIcon), {class: "h-5 w-5"})], 8, ["onClick"])])) : createCommentVNode("", true),
                                    unref(form).errors.file ? (openBlock(), createBlock("p", {
                                        key: 1,
                                        class: "text-sm text-red-600"
                                    }, toDisplayString(unref(form).errors.file), 1)) : createCommentVNode("", true)
                                ])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
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
                            href: "/documents",
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
                                if (_push) _push(`${ssrInterpolate(unref(form).processing ? "Uploading..." : "Save Document")}`);
                                else return [createTextVNode(toDisplayString(unref(form).processing ? "Uploading..." : "Save Document"), 1)];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`</div></form></div>`);
                    } else return [createVNode("div", {class: "mx-auto max-w-3xl space-y-6"}, [createVNode("header", null, [createVNode("h1", {class: "text-3xl font-bold tracking-tight text-zinc-900"}, "Upload Document"), createVNode("p", {class: "mt-1 text-sm text-zinc-500"}, "Add a new document to your library. PDF, JPG, PNG, and WebP are supported.")]), createVNode("form", {
                        onSubmit: withModifiers(submit, ["prevent"]),
                        class: "space-y-6"
                    }, [
                        createVNode(_sfc_main$2, {title: "Document File"}, {
                            default: withCtx(() => [createVNode("div", {class: "space-y-4"}, [
                                createVNode("div", {class: "flex items-center justify-center rounded-lg border-2 border-dashed border-zinc-200 bg-zinc-50/50 p-6 transition-colors hover:border-zinc-300"}, [createVNode("div", {class: "text-center"}, [
                                    createVNode(unref(CloudArrowUpIcon), {class: "mx-auto h-10 w-10 text-zinc-400"}),
                                    createVNode("div", {class: "mt-4 flex text-sm text-zinc-600"}, [createVNode("label", {class: "relative cursor-pointer rounded-md font-semibold text-zinc-900 hover:text-zinc-700"}, [createVNode("span", null, "Select a file"), createVNode("input", {
                                        type: "file",
                                        accept: ".pdf,.jpg,.jpeg,.png,.webp",
                                        onInput: ($event) => unref(form).file = $event.target.files[0],
                                        class: "sr-only"
                                    }, null, 40, ["onInput"])]), createVNode("p", {class: "pl-1"}, "or drag and drop")]),
                                    createVNode("p", {class: "text-xs text-zinc-500"}, "PDF, PNG, JPG up to 10MB")
                                ])]),
                                unref(form).file ? (openBlock(), createBlock("div", {
                                    key: 0,
                                    class: "flex items-center justify-between rounded-lg bg-zinc-100 px-4 py-2 text-sm text-zinc-700"
                                }, [createVNode("span", {class: "truncate font-medium"}, toDisplayString(unref(form).file.name), 1), createVNode("button", {
                                    type: "button",
                                    onClick: ($event) => unref(form).file = null,
                                    class: "text-zinc-400 hover:text-zinc-600"
                                }, [createVNode(unref(XMarkIcon), {class: "h-5 w-5"})], 8, ["onClick"])])) : createCommentVNode("", true),
                                unref(form).errors.file ? (openBlock(), createBlock("p", {
                                    key: 1,
                                    class: "text-sm text-red-600"
                                }, toDisplayString(unref(form).errors.file), 1)) : createCommentVNode("", true)
                            ])]),
                            _: 1
                        }),
                        createVNode(_sfc_main$2, {title: "General Information"}, {
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
                        }),
                        createVNode("div", {class: "flex items-center justify-end gap-3"}, [createVNode(_sfc_main$3, {
                            href: "/documents",
                            variant: "secondary"
                        }, {
                            default: withCtx(() => [createTextVNode("Cancel")]),
                            _: 1
                        }), createVNode(_sfc_main$3, {
                            type: "submit",
                            disabled: unref(form).processing
                        }, {
                            default: withCtx(() => [createTextVNode(toDisplayString(unref(form).processing ? "Uploading..." : "Save Document"), 1)]),
                            _: 1
                        }, 8, ["disabled"])])
                    ], 32)])];
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
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Documents/Create.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main as default};

//# sourceMappingURL=Create-BjfE9_9i.js.map
