import {n as _sfc_main$1, t as _sfc_main$2} from "./Card-Cxv5bgu1.js";
import {t as _sfc_main$3} from "./Button-BKBm8czA.js";
import {n as _sfc_main$4, t as _sfc_main$5} from "./Select-CzueStu9.js";
import {t as _sfc_main$6} from "./Badge-DXJPokTZ.js";
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import {
    createBlock,
    createCommentVNode,
    createSlots,
    createTextVNode,
    createVNode,
    Fragment,
    openBlock,
    renderList,
    toDisplayString,
    unref,
    useSSRContext,
    watch,
    withCtx,
    withModifiers
} from "vue";
import {ssrInterpolate, ssrRenderClass, ssrRenderComponent, ssrRenderList} from "vue/server-renderer";
import {ChevronDownIcon, ChevronUpIcon, CloudArrowUpIcon, DocumentIcon} from "@heroicons/vue/24/outline";
import debounce from "lodash/debounce.js";
//#region resources/js/Pages/Documents/Index.vue
var _sfc_main = {
    __name: "Index",
    __ssrInlineRender: true,
    props: {
        documents: Object,
        filters: Object,
        categories: Array,
        tags: Array
    },
    setup(__props) {
        const props = __props;
        const form = useForm({
            q: props.filters.q || "",
            category_id: props.filters.category_id || "",
            status: props.filters.status || "",
            sort: props.filters.sort || "newest",
            direction: props.filters.direction || ""
        });
        const sortBy = (field) => {
            if (form.sort === field) form.direction = form.direction === "asc" ? "desc" : "asc";
            else {
                form.sort = field;
                form.direction = "asc";
            }
        };
        const getStatusVariant = (status) => {
            switch (status) {
                case "active":
                    return "success";
                case "expired":
                    return "danger";
                case "archived":
                    return "neutral";
                default:
                    return "neutral";
            }
        };
        const applyFilters = () => {
            form.get("/documents", {
                preserveState: true,
                replace: true
            });
        };
        watch(() => form.data(), debounce((data) => {
            applyFilters();
        }, 300), {deep: true});
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<!--[-->`);
            _push(ssrRenderComponent(unref(Head), {title: "Documents"}, null, _parent));
            _push(ssrRenderComponent(_sfc_main$1, null, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) {
                        _push(`<div class="space-y-6"${_scopeId}><header class="flex items-center justify-between"${_scopeId}><div${_scopeId}><h1 class="text-3xl font-bold tracking-tight text-zinc-900"${_scopeId}>Documents</h1><p class="mt-1 text-sm text-zinc-500"${_scopeId}>Manage and filter your document collection.</p></div>`);
                        _push(ssrRenderComponent(_sfc_main$3, {href: "/documents/create"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(ssrRenderComponent(unref(CloudArrowUpIcon), {class: "mr-2 h-5 w-5"}, null, _parent, _scopeId));
                                    _push(` Upload Document `);
                                } else return [createVNode(unref(CloudArrowUpIcon), {class: "mr-2 h-5 w-5"}), createTextVNode(" Upload Document ")];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`</header>`);
                        _push(ssrRenderComponent(_sfc_main$2, {padding: "px-4 py-3"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<form class="flex flex-wrap items-center gap-3"${_scopeId}><div class="min-w-[240px] flex-1"${_scopeId}>`);
                                    _push(ssrRenderComponent(_sfc_main$4, {
                                        modelValue: unref(form).q,
                                        "onUpdate:modelValue": ($event) => unref(form).q = $event,
                                        placeholder: "Search documents..."
                                    }, null, _parent, _scopeId));
                                    _push(`</div>`);
                                    _push(ssrRenderComponent(_sfc_main$5, {
                                        modelValue: unref(form).category_id,
                                        "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                                        options: __props.categories.map((c) => ({
                                            value: c.id,
                                            label: c.name
                                        })),
                                        placeholder: "All categories",
                                        class: "min-w-[160px]"
                                    }, null, _parent, _scopeId));
                                    _push(ssrRenderComponent(_sfc_main$5, {
                                        modelValue: unref(form).status,
                                        "onUpdate:modelValue": ($event) => unref(form).status = $event,
                                        options: [
                                            {
                                                value: "active",
                                                label: "Active"
                                            },
                                            {
                                                value: "expired",
                                                label: "Expired"
                                            },
                                            {
                                                value: "archived",
                                                label: "Archived"
                                            }
                                        ],
                                        placeholder: "All statuses",
                                        class: "min-w-[140px]"
                                    }, null, _parent, _scopeId));
                                    _push(`</form>`);
                                } else return [createVNode("form", {
                                    class: "flex flex-wrap items-center gap-3",
                                    onSubmit: withModifiers(applyFilters, ["prevent"])
                                }, [
                                    createVNode("div", {class: "min-w-[240px] flex-1"}, [createVNode(_sfc_main$4, {
                                        modelValue: unref(form).q,
                                        "onUpdate:modelValue": ($event) => unref(form).q = $event,
                                        placeholder: "Search documents..."
                                    }, null, 8, ["modelValue", "onUpdate:modelValue"])]),
                                    createVNode(_sfc_main$5, {
                                        modelValue: unref(form).category_id,
                                        "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                                        options: __props.categories.map((c) => ({
                                            value: c.id,
                                            label: c.name
                                        })),
                                        placeholder: "All categories",
                                        class: "min-w-[160px]"
                                    }, null, 8, [
                                        "modelValue",
                                        "onUpdate:modelValue",
                                        "options"
                                    ]),
                                    createVNode(_sfc_main$5, {
                                        modelValue: unref(form).status,
                                        "onUpdate:modelValue": ($event) => unref(form).status = $event,
                                        options: [
                                            {
                                                value: "active",
                                                label: "Active"
                                            },
                                            {
                                                value: "expired",
                                                label: "Expired"
                                            },
                                            {
                                                value: "archived",
                                                label: "Archived"
                                            }
                                        ],
                                        placeholder: "All statuses",
                                        class: "min-w-[140px]"
                                    }, null, 8, ["modelValue", "onUpdate:modelValue"])
                                ], 32)];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(ssrRenderComponent(_sfc_main$2, {padding: "p-0"}, createSlots({
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="overflow-x-auto"${_scopeId}><table class="w-full text-left text-sm text-zinc-900"${_scopeId}><thead class="bg-zinc-50/50 text-xs font-semibold uppercase tracking-wider text-zinc-500"${_scopeId}><tr${_scopeId}><th class="px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors"${_scopeId}><div class="flex items-center gap-1"${_scopeId}> Document `);
                                    if (unref(form).sort === "title") {
                                        _push(`<!--[-->`);
                                        if (unref(form).direction === "asc") _push(ssrRenderComponent(unref(ChevronUpIcon), {class: "h-3 w-3"}, null, _parent, _scopeId));
                                        else _push(ssrRenderComponent(unref(ChevronDownIcon), {class: "h-3 w-3"}, null, _parent, _scopeId));
                                        _push(`<!--]-->`);
                                    } else _push(`<!---->`);
                                    _push(`</div></th><th class="px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors"${_scopeId}><div class="flex items-center gap-1"${_scopeId}> Category `);
                                    if (unref(form).sort === "category") {
                                        _push(`<!--[-->`);
                                        if (unref(form).direction === "asc") _push(ssrRenderComponent(unref(ChevronUpIcon), {class: "h-3 w-3"}, null, _parent, _scopeId));
                                        else _push(ssrRenderComponent(unref(ChevronDownIcon), {class: "h-3 w-3"}, null, _parent, _scopeId));
                                        _push(`<!--]-->`);
                                    } else _push(`<!---->`);
                                    _push(`</div></th><th class="px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors"${_scopeId}><div class="flex items-center gap-1"${_scopeId}> Status `);
                                    if (unref(form).sort === "status") {
                                        _push(`<!--[-->`);
                                        if (unref(form).direction === "asc") _push(ssrRenderComponent(unref(ChevronUpIcon), {class: "h-3 w-3"}, null, _parent, _scopeId));
                                        else _push(ssrRenderComponent(unref(ChevronDownIcon), {class: "h-3 w-3"}, null, _parent, _scopeId));
                                        _push(`<!--]-->`);
                                    } else _push(`<!---->`);
                                    _push(`</div></th><th class="px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors"${_scopeId}><div class="flex items-center gap-1"${_scopeId}> Expiry `);
                                    if (unref(form).sort === "expiry_date") {
                                        _push(`<!--[-->`);
                                        if (unref(form).direction === "asc") _push(ssrRenderComponent(unref(ChevronUpIcon), {class: "h-3 w-3"}, null, _parent, _scopeId));
                                        else _push(ssrRenderComponent(unref(ChevronDownIcon), {class: "h-3 w-3"}, null, _parent, _scopeId));
                                        _push(`<!--]-->`);
                                    } else _push(`<!---->`);
                                    _push(`</div></th></tr></thead><tbody class="divide-y divide-zinc-100"${_scopeId}><!--[-->`);
                                    ssrRenderList(__props.documents.data, (document) => {
                                        _push(`<tr class="group cursor-pointer hover:bg-zinc-50/50 transition-colors"${_scopeId}><td class="px-6 py-4"${_scopeId}><div class="font-semibold text-zinc-900 group-hover:text-zinc-600"${_scopeId}>${ssrInterpolate(document.title)}</div><p class="text-xs text-zinc-500"${_scopeId}>Updated ${ssrInterpolate(document.updated_at)}</p></td><td class="px-6 py-4"${_scopeId}>`);
                                        if (document.category) _push(ssrRenderComponent(_sfc_main$6, {variant: "neutral"}, {
                                            default: withCtx((_, _push, _parent, _scopeId) => {
                                                if (_push) _push(`${ssrInterpolate(document.category.name)}`);
                                                else return [createTextVNode(toDisplayString(document.category.name), 1)];
                                            }),
                                            _: 2
                                        }, _parent, _scopeId));
                                        else _push(`<span class="text-zinc-400"${_scopeId}>—</span>`);
                                        _push(`</td><td class="px-6 py-4"${_scopeId}>`);
                                        _push(ssrRenderComponent(_sfc_main$6, {variant: getStatusVariant(document.status)}, {
                                            default: withCtx((_, _push, _parent, _scopeId) => {
                                                if (_push) _push(`${ssrInterpolate(document.status)}`);
                                                else return [createTextVNode(toDisplayString(document.status), 1)];
                                            }),
                                            _: 2
                                        }, _parent, _scopeId));
                                        _push(`</td><td class="px-6 py-4"${_scopeId}><span class="${ssrRenderClass(document.status === "expired" ? "font-medium text-red-600" : "text-zinc-600")}"${_scopeId}>${ssrInterpolate(document.expiry_date ?? "—")}</span></td></tr>`);
                                    });
                                    _push(`<!--]-->`);
                                    if (__props.documents.data.length === 0) {
                                        _push(`<tr${_scopeId}><td class="px-6 py-12 text-center text-zinc-500" colspan="4"${_scopeId}><div class="flex flex-col items-center gap-2"${_scopeId}>`);
                                        _push(ssrRenderComponent(unref(DocumentIcon), {class: "h-8 w-8 text-zinc-300"}, null, _parent, _scopeId));
                                        _push(`<p${_scopeId}>No documents found matching your criteria.</p></div></td></tr>`);
                                    } else _push(`<!---->`);
                                    _push(`</tbody></table></div>`);
                                } else return [createVNode("div", {class: "overflow-x-auto"}, [createVNode("table", {class: "w-full text-left text-sm text-zinc-900"}, [createVNode("thead", {class: "bg-zinc-50/50 text-xs font-semibold uppercase tracking-wider text-zinc-500"}, [createVNode("tr", null, [
                                    createVNode("th", {
                                        class: "px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors",
                                        onClick: ($event) => sortBy("title")
                                    }, [createVNode("div", {class: "flex items-center gap-1"}, [createTextVNode(" Document "), unref(form).sort === "title" ? (openBlock(), createBlock(Fragment, {key: 0}, [unref(form).direction === "asc" ? (openBlock(), createBlock(unref(ChevronUpIcon), {
                                        key: 0,
                                        class: "h-3 w-3"
                                    })) : (openBlock(), createBlock(unref(ChevronDownIcon), {
                                        key: 1,
                                        class: "h-3 w-3"
                                    }))], 64)) : createCommentVNode("", true)])], 8, ["onClick"]),
                                    createVNode("th", {
                                        class: "px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors",
                                        onClick: ($event) => sortBy("category")
                                    }, [createVNode("div", {class: "flex items-center gap-1"}, [createTextVNode(" Category "), unref(form).sort === "category" ? (openBlock(), createBlock(Fragment, {key: 0}, [unref(form).direction === "asc" ? (openBlock(), createBlock(unref(ChevronUpIcon), {
                                        key: 0,
                                        class: "h-3 w-3"
                                    })) : (openBlock(), createBlock(unref(ChevronDownIcon), {
                                        key: 1,
                                        class: "h-3 w-3"
                                    }))], 64)) : createCommentVNode("", true)])], 8, ["onClick"]),
                                    createVNode("th", {
                                        class: "px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors",
                                        onClick: ($event) => sortBy("status")
                                    }, [createVNode("div", {class: "flex items-center gap-1"}, [createTextVNode(" Status "), unref(form).sort === "status" ? (openBlock(), createBlock(Fragment, {key: 0}, [unref(form).direction === "asc" ? (openBlock(), createBlock(unref(ChevronUpIcon), {
                                        key: 0,
                                        class: "h-3 w-3"
                                    })) : (openBlock(), createBlock(unref(ChevronDownIcon), {
                                        key: 1,
                                        class: "h-3 w-3"
                                    }))], 64)) : createCommentVNode("", true)])], 8, ["onClick"]),
                                    createVNode("th", {
                                        class: "px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors",
                                        onClick: ($event) => sortBy("expiry_date")
                                    }, [createVNode("div", {class: "flex items-center gap-1"}, [createTextVNode(" Expiry "), unref(form).sort === "expiry_date" ? (openBlock(), createBlock(Fragment, {key: 0}, [unref(form).direction === "asc" ? (openBlock(), createBlock(unref(ChevronUpIcon), {
                                        key: 0,
                                        class: "h-3 w-3"
                                    })) : (openBlock(), createBlock(unref(ChevronDownIcon), {
                                        key: 1,
                                        class: "h-3 w-3"
                                    }))], 64)) : createCommentVNode("", true)])], 8, ["onClick"])
                                ])]), createVNode("tbody", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.documents.data, (document) => {
                                    return openBlock(), createBlock("tr", {
                                        key: document.id,
                                        class: "group cursor-pointer hover:bg-zinc-50/50 transition-colors",
                                        onClick: ($event) => unref(router).visit(`/documents/${document.id}`)
                                    }, [
                                        createVNode("td", {class: "px-6 py-4"}, [createVNode("div", {class: "font-semibold text-zinc-900 group-hover:text-zinc-600"}, toDisplayString(document.title), 1), createVNode("p", {class: "text-xs text-zinc-500"}, "Updated " + toDisplayString(document.updated_at), 1)]),
                                        createVNode("td", {class: "px-6 py-4"}, [document.category ? (openBlock(), createBlock(_sfc_main$6, {
                                            key: 0,
                                            variant: "neutral"
                                        }, {
                                            default: withCtx(() => [createTextVNode(toDisplayString(document.category.name), 1)]),
                                            _: 2
                                        }, 1024)) : (openBlock(), createBlock("span", {
                                            key: 1,
                                            class: "text-zinc-400"
                                        }, "—"))]),
                                        createVNode("td", {class: "px-6 py-4"}, [createVNode(_sfc_main$6, {variant: getStatusVariant(document.status)}, {
                                            default: withCtx(() => [createTextVNode(toDisplayString(document.status), 1)]),
                                            _: 2
                                        }, 1032, ["variant"])]),
                                        createVNode("td", {class: "px-6 py-4"}, [createVNode("span", {class: document.status === "expired" ? "font-medium text-red-600" : "text-zinc-600"}, toDisplayString(document.expiry_date ?? "—"), 3)])
                                    ], 8, ["onClick"]);
                                }), 128)), __props.documents.data.length === 0 ? (openBlock(), createBlock("tr", {key: 0}, [createVNode("td", {
                                    class: "px-6 py-12 text-center text-zinc-500",
                                    colspan: "4"
                                }, [createVNode("div", {class: "flex flex-col items-center gap-2"}, [createVNode(unref(DocumentIcon), {class: "h-8 w-8 text-zinc-300"}), createVNode("p", null, "No documents found matching your criteria.")])])])) : createCommentVNode("", true)])])])];
                            }),
                            _: 2
                        }, [__props.documents.links.length > 3 ? {
                            name: "footer",
                            fn: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="flex items-center justify-between"${_scopeId}><div class="text-xs text-zinc-500"${_scopeId}> Showing ${ssrInterpolate(__props.documents.from)} to ${ssrInterpolate(__props.documents.to)} of ${ssrInterpolate(__props.documents.total)} results </div><div class="flex gap-1"${_scopeId}><!--[-->`);
                                    ssrRenderList(__props.documents.links, (link) => {
                                        _push(ssrRenderComponent(unref(Link), {
                                            key: link.label,
                                            href: link.url,
                                            class: [
                                                "rounded px-2 py-1 text-xs font-medium",
                                                link.active ? "bg-zinc-900 text-white" : "text-zinc-600 hover:bg-zinc-100",
                                                !link.url ? "pointer-events-none opacity-50" : ""
                                            ]
                                        }, null, _parent, _scopeId));
                                    });
                                    _push(`<!--]--></div></div>`);
                                } else return [createVNode("div", {class: "flex items-center justify-between"}, [createVNode("div", {class: "text-xs text-zinc-500"}, " Showing " + toDisplayString(__props.documents.from) + " to " + toDisplayString(__props.documents.to) + " of " + toDisplayString(__props.documents.total) + " results ", 1), createVNode("div", {class: "flex gap-1"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.documents.links, (link) => {
                                    return openBlock(), createBlock(unref(Link), {
                                        key: link.label,
                                        href: link.url,
                                        innerHTML: link.label,
                                        class: [
                                            "rounded px-2 py-1 text-xs font-medium",
                                            link.active ? "bg-zinc-900 text-white" : "text-zinc-600 hover:bg-zinc-100",
                                            !link.url ? "pointer-events-none opacity-50" : ""
                                        ]
                                    }, null, 8, [
                                        "href",
                                        "innerHTML",
                                        "class"
                                    ]);
                                }), 128))])])];
                            }),
                            key: "0"
                        } : void 0]), _parent, _scopeId));
                        _push(`</div>`);
                    } else return [createVNode("div", {class: "space-y-6"}, [
                        createVNode("header", {class: "flex items-center justify-between"}, [createVNode("div", null, [createVNode("h1", {class: "text-3xl font-bold tracking-tight text-zinc-900"}, "Documents"), createVNode("p", {class: "mt-1 text-sm text-zinc-500"}, "Manage and filter your document collection.")]), createVNode(_sfc_main$3, {href: "/documents/create"}, {
                            default: withCtx(() => [createVNode(unref(CloudArrowUpIcon), {class: "mr-2 h-5 w-5"}), createTextVNode(" Upload Document ")]),
                            _: 1
                        })]),
                        createVNode(_sfc_main$2, {padding: "px-4 py-3"}, {
                            default: withCtx(() => [createVNode("form", {
                                class: "flex flex-wrap items-center gap-3",
                                onSubmit: withModifiers(applyFilters, ["prevent"])
                            }, [
                                createVNode("div", {class: "min-w-[240px] flex-1"}, [createVNode(_sfc_main$4, {
                                    modelValue: unref(form).q,
                                    "onUpdate:modelValue": ($event) => unref(form).q = $event,
                                    placeholder: "Search documents..."
                                }, null, 8, ["modelValue", "onUpdate:modelValue"])]),
                                createVNode(_sfc_main$5, {
                                    modelValue: unref(form).category_id,
                                    "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                                    options: __props.categories.map((c) => ({
                                        value: c.id,
                                        label: c.name
                                    })),
                                    placeholder: "All categories",
                                    class: "min-w-[160px]"
                                }, null, 8, [
                                    "modelValue",
                                    "onUpdate:modelValue",
                                    "options"
                                ]),
                                createVNode(_sfc_main$5, {
                                    modelValue: unref(form).status,
                                    "onUpdate:modelValue": ($event) => unref(form).status = $event,
                                    options: [
                                        {
                                            value: "active",
                                            label: "Active"
                                        },
                                        {
                                            value: "expired",
                                            label: "Expired"
                                        },
                                        {
                                            value: "archived",
                                            label: "Archived"
                                        }
                                    ],
                                    placeholder: "All statuses",
                                    class: "min-w-[140px]"
                                }, null, 8, ["modelValue", "onUpdate:modelValue"])
                            ], 32)]),
                            _: 1
                        }),
                        createVNode(_sfc_main$2, {padding: "p-0"}, createSlots({
                            default: withCtx(() => [createVNode("div", {class: "overflow-x-auto"}, [createVNode("table", {class: "w-full text-left text-sm text-zinc-900"}, [createVNode("thead", {class: "bg-zinc-50/50 text-xs font-semibold uppercase tracking-wider text-zinc-500"}, [createVNode("tr", null, [
                                createVNode("th", {
                                    class: "px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors",
                                    onClick: ($event) => sortBy("title")
                                }, [createVNode("div", {class: "flex items-center gap-1"}, [createTextVNode(" Document "), unref(form).sort === "title" ? (openBlock(), createBlock(Fragment, {key: 0}, [unref(form).direction === "asc" ? (openBlock(), createBlock(unref(ChevronUpIcon), {
                                    key: 0,
                                    class: "h-3 w-3"
                                })) : (openBlock(), createBlock(unref(ChevronDownIcon), {
                                    key: 1,
                                    class: "h-3 w-3"
                                }))], 64)) : createCommentVNode("", true)])], 8, ["onClick"]),
                                createVNode("th", {
                                    class: "px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors",
                                    onClick: ($event) => sortBy("category")
                                }, [createVNode("div", {class: "flex items-center gap-1"}, [createTextVNode(" Category "), unref(form).sort === "category" ? (openBlock(), createBlock(Fragment, {key: 0}, [unref(form).direction === "asc" ? (openBlock(), createBlock(unref(ChevronUpIcon), {
                                    key: 0,
                                    class: "h-3 w-3"
                                })) : (openBlock(), createBlock(unref(ChevronDownIcon), {
                                    key: 1,
                                    class: "h-3 w-3"
                                }))], 64)) : createCommentVNode("", true)])], 8, ["onClick"]),
                                createVNode("th", {
                                    class: "px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors",
                                    onClick: ($event) => sortBy("status")
                                }, [createVNode("div", {class: "flex items-center gap-1"}, [createTextVNode(" Status "), unref(form).sort === "status" ? (openBlock(), createBlock(Fragment, {key: 0}, [unref(form).direction === "asc" ? (openBlock(), createBlock(unref(ChevronUpIcon), {
                                    key: 0,
                                    class: "h-3 w-3"
                                })) : (openBlock(), createBlock(unref(ChevronDownIcon), {
                                    key: 1,
                                    class: "h-3 w-3"
                                }))], 64)) : createCommentVNode("", true)])], 8, ["onClick"]),
                                createVNode("th", {
                                    class: "px-6 py-4 cursor-pointer hover:text-zinc-900 transition-colors",
                                    onClick: ($event) => sortBy("expiry_date")
                                }, [createVNode("div", {class: "flex items-center gap-1"}, [createTextVNode(" Expiry "), unref(form).sort === "expiry_date" ? (openBlock(), createBlock(Fragment, {key: 0}, [unref(form).direction === "asc" ? (openBlock(), createBlock(unref(ChevronUpIcon), {
                                    key: 0,
                                    class: "h-3 w-3"
                                })) : (openBlock(), createBlock(unref(ChevronDownIcon), {
                                    key: 1,
                                    class: "h-3 w-3"
                                }))], 64)) : createCommentVNode("", true)])], 8, ["onClick"])
                            ])]), createVNode("tbody", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.documents.data, (document) => {
                                return openBlock(), createBlock("tr", {
                                    key: document.id,
                                    class: "group cursor-pointer hover:bg-zinc-50/50 transition-colors",
                                    onClick: ($event) => unref(router).visit(`/documents/${document.id}`)
                                }, [
                                    createVNode("td", {class: "px-6 py-4"}, [createVNode("div", {class: "font-semibold text-zinc-900 group-hover:text-zinc-600"}, toDisplayString(document.title), 1), createVNode("p", {class: "text-xs text-zinc-500"}, "Updated " + toDisplayString(document.updated_at), 1)]),
                                    createVNode("td", {class: "px-6 py-4"}, [document.category ? (openBlock(), createBlock(_sfc_main$6, {
                                        key: 0,
                                        variant: "neutral"
                                    }, {
                                        default: withCtx(() => [createTextVNode(toDisplayString(document.category.name), 1)]),
                                        _: 2
                                    }, 1024)) : (openBlock(), createBlock("span", {
                                        key: 1,
                                        class: "text-zinc-400"
                                    }, "—"))]),
                                    createVNode("td", {class: "px-6 py-4"}, [createVNode(_sfc_main$6, {variant: getStatusVariant(document.status)}, {
                                        default: withCtx(() => [createTextVNode(toDisplayString(document.status), 1)]),
                                        _: 2
                                    }, 1032, ["variant"])]),
                                    createVNode("td", {class: "px-6 py-4"}, [createVNode("span", {class: document.status === "expired" ? "font-medium text-red-600" : "text-zinc-600"}, toDisplayString(document.expiry_date ?? "—"), 3)])
                                ], 8, ["onClick"]);
                            }), 128)), __props.documents.data.length === 0 ? (openBlock(), createBlock("tr", {key: 0}, [createVNode("td", {
                                class: "px-6 py-12 text-center text-zinc-500",
                                colspan: "4"
                            }, [createVNode("div", {class: "flex flex-col items-center gap-2"}, [createVNode(unref(DocumentIcon), {class: "h-8 w-8 text-zinc-300"}), createVNode("p", null, "No documents found matching your criteria.")])])])) : createCommentVNode("", true)])])])]),
                            _: 2
                        }, [__props.documents.links.length > 3 ? {
                            name: "footer",
                            fn: withCtx(() => [createVNode("div", {class: "flex items-center justify-between"}, [createVNode("div", {class: "text-xs text-zinc-500"}, " Showing " + toDisplayString(__props.documents.from) + " to " + toDisplayString(__props.documents.to) + " of " + toDisplayString(__props.documents.total) + " results ", 1), createVNode("div", {class: "flex gap-1"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.documents.links, (link) => {
                                return openBlock(), createBlock(unref(Link), {
                                    key: link.label,
                                    href: link.url,
                                    innerHTML: link.label,
                                    class: [
                                        "rounded px-2 py-1 text-xs font-medium",
                                        link.active ? "bg-zinc-900 text-white" : "text-zinc-600 hover:bg-zinc-100",
                                        !link.url ? "pointer-events-none opacity-50" : ""
                                    ]
                                }, null, 8, [
                                    "href",
                                    "innerHTML",
                                    "class"
                                ]);
                            }), 128))])])]),
                            key: "0"
                        } : void 0]), 1024)
                    ])];
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
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Documents/Index.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main as default};

//# sourceMappingURL=Index-RLUyPoGl.js.map
