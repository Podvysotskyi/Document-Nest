import {n as _sfc_main$1, t as _sfc_main$2} from "./Card-Cxv5bgu1.js";
import {Head, Link} from "@inertiajs/vue3";
import {
    createBlock,
    createCommentVNode,
    createTextVNode,
    createVNode,
    Fragment,
    openBlock,
    renderList,
    toDisplayString,
    unref,
    useSSRContext,
    withCtx
} from "vue";
import {ssrInterpolate, ssrRenderComponent, ssrRenderList} from "vue/server-renderer";
import {CalendarIcon, DocumentTextIcon, ExclamationCircleIcon} from "@heroicons/vue/24/outline";
//#region resources/js/Pages/Dashboard.vue
var _sfc_main = {
    __name: "Dashboard",
    __ssrInlineRender: true,
    props: {
        stats: Object,
        recent_uploads: Array,
        expiring_soon: Array,
        missing_expiry: Array,
        documents_by_category: Array
    },
    setup(__props) {
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<!--[-->`);
            _push(ssrRenderComponent(unref(Head), {title: "Dashboard"}, null, _parent));
            _push(ssrRenderComponent(_sfc_main$1, null, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) {
                        _push(`<div class="space-y-8"${_scopeId}><header${_scopeId}><h1 class="text-3xl font-bold tracking-tight text-zinc-900"${_scopeId}>Dashboard</h1><p class="mt-1 text-sm text-zinc-500"${_scopeId}>Overview of your document library and upcoming expirations.</p></header><section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"${_scopeId}>`);
                        _push(ssrRenderComponent(_sfc_main$2, {padding: "px-6 py-6"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="flex items-center gap-4"${_scopeId}><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-900"${_scopeId}>`);
                                    _push(ssrRenderComponent(unref(DocumentTextIcon), {class: "h-6 w-6"}, null, _parent, _scopeId));
                                    _push(`</div><div${_scopeId}><p class="text-sm font-medium text-zinc-500"${_scopeId}>Total documents</p><p class="text-3xl font-bold text-zinc-900"${_scopeId}>${ssrInterpolate(__props.stats.total_documents)}</p></div></div>`);
                                } else return [createVNode("div", {class: "flex items-center gap-4"}, [createVNode("div", {class: "flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-900"}, [createVNode(unref(DocumentTextIcon), {class: "h-6 w-6"})]), createVNode("div", null, [createVNode("p", {class: "text-sm font-medium text-zinc-500"}, "Total documents"), createVNode("p", {class: "text-3xl font-bold text-zinc-900"}, toDisplayString(__props.stats.total_documents), 1)])])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(ssrRenderComponent(_sfc_main$2, {padding: "px-6 py-6"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="flex items-center gap-4"${_scopeId}><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600"${_scopeId}>`);
                                    _push(ssrRenderComponent(unref(CalendarIcon), {class: "h-6 w-6"}, null, _parent, _scopeId));
                                    _push(`</div><div${_scopeId}><p class="text-sm font-medium text-zinc-500"${_scopeId}>Expiring in 30 days</p><p class="text-3xl font-bold text-zinc-900"${_scopeId}>${ssrInterpolate(__props.stats.expiring_soon_count)}</p></div></div>`);
                                } else return [createVNode("div", {class: "flex items-center gap-4"}, [createVNode("div", {class: "flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600"}, [createVNode(unref(CalendarIcon), {class: "h-6 w-6"})]), createVNode("div", null, [createVNode("p", {class: "text-sm font-medium text-zinc-500"}, "Expiring in 30 days"), createVNode("p", {class: "text-3xl font-bold text-zinc-900"}, toDisplayString(__props.stats.expiring_soon_count), 1)])])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(ssrRenderComponent(_sfc_main$2, {padding: "px-6 py-6"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="flex items-center gap-4"${_scopeId}><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600"${_scopeId}>`);
                                    _push(ssrRenderComponent(unref(ExclamationCircleIcon), {class: "h-6 w-6"}, null, _parent, _scopeId));
                                    _push(`</div><div${_scopeId}><p class="text-sm font-medium text-zinc-500"${_scopeId}>Missing expiry date</p><p class="text-3xl font-bold text-zinc-900"${_scopeId}>${ssrInterpolate(__props.stats.missing_expiry_count)}</p></div></div>`);
                                } else return [createVNode("div", {class: "flex items-center gap-4"}, [createVNode("div", {class: "flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600"}, [createVNode(unref(ExclamationCircleIcon), {class: "h-6 w-6"})]), createVNode("div", null, [createVNode("p", {class: "text-sm font-medium text-zinc-500"}, "Missing expiry date"), createVNode("p", {class: "text-3xl font-bold text-zinc-900"}, toDisplayString(__props.stats.missing_expiry_count), 1)])])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`</section><div class="grid gap-6 lg:grid-cols-2"${_scopeId}>`);
                        _push(ssrRenderComponent(_sfc_main$2, {
                            title: "Recent uploads",
                            padding: "p-0"
                        }, {
                            footer: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) _push(ssrRenderComponent(unref(Link), {
                                    href: "/documents",
                                    class: "text-xs font-semibold text-zinc-900 hover:underline"
                                }, {
                                    default: withCtx((_, _push, _parent, _scopeId) => {
                                        if (_push) _push(`View all documents →`);
                                        else return [createTextVNode("View all documents →")];
                                    }),
                                    _: 1
                                }, _parent, _scopeId));
                                else return [createVNode(unref(Link), {
                                    href: "/documents",
                                    class: "text-xs font-semibold text-zinc-900 hover:underline"
                                }, {
                                    default: withCtx(() => [createTextVNode("View all documents →")]),
                                    _: 1
                                })];
                            }),
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="divide-y divide-zinc-100"${_scopeId}><!--[-->`);
                                    ssrRenderList(__props.recent_uploads, (document) => {
                                        _push(`<div class="flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"${_scopeId}><div${_scopeId}><p class="text-sm font-semibold text-zinc-900"${_scopeId}>${ssrInterpolate(document.title)}</p><p class="text-xs text-zinc-500"${_scopeId}>${ssrInterpolate(document.updated_at)}</p></div><span class="rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-zinc-600"${_scopeId}>${ssrInterpolate(document.status)}</span></div>`);
                                    });
                                    _push(`<!--]-->`);
                                    if (__props.recent_uploads.length === 0) _push(`<div class="px-6 py-12 text-center text-sm text-zinc-500"${_scopeId}> No uploads yet. </div>`);
                                    else _push(`<!---->`);
                                    _push(`</div>`);
                                } else return [createVNode("div", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.recent_uploads, (document) => {
                                    return openBlock(), createBlock("div", {
                                        key: document.id,
                                        class: "flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"
                                    }, [createVNode("div", null, [createVNode("p", {class: "text-sm font-semibold text-zinc-900"}, toDisplayString(document.title), 1), createVNode("p", {class: "text-xs text-zinc-500"}, toDisplayString(document.updated_at), 1)]), createVNode("span", {class: "rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-zinc-600"}, toDisplayString(document.status), 1)]);
                                }), 128)), __props.recent_uploads.length === 0 ? (openBlock(), createBlock("div", {
                                    key: 0,
                                    class: "px-6 py-12 text-center text-sm text-zinc-500"
                                }, " No uploads yet. ")) : createCommentVNode("", true)])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(ssrRenderComponent(_sfc_main$2, {
                            title: "Expiring soon",
                            padding: "p-0"
                        }, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="divide-y divide-zinc-100"${_scopeId}><!--[-->`);
                                    ssrRenderList(__props.expiring_soon, (document) => {
                                        _push(`<div class="flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"${_scopeId}><div${_scopeId}><p class="text-sm font-semibold text-zinc-900"${_scopeId}>${ssrInterpolate(document.title)}</p><p class="text-xs text-zinc-500"${_scopeId}>Expires on ${ssrInterpolate(document.expiry_date)}</p></div><div class="flex h-2 w-2 rounded-full bg-amber-500"${_scopeId}></div></div>`);
                                    });
                                    _push(`<!--]-->`);
                                    if (__props.expiring_soon.length === 0) _push(`<div class="px-6 py-12 text-center text-sm text-zinc-500"${_scopeId}> No documents expiring soon. </div>`);
                                    else _push(`<!---->`);
                                    _push(`</div>`);
                                } else return [createVNode("div", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.expiring_soon, (document) => {
                                    return openBlock(), createBlock("div", {
                                        key: document.id,
                                        class: "flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"
                                    }, [createVNode("div", null, [createVNode("p", {class: "text-sm font-semibold text-zinc-900"}, toDisplayString(document.title), 1), createVNode("p", {class: "text-xs text-zinc-500"}, "Expires on " + toDisplayString(document.expiry_date), 1)]), createVNode("div", {class: "flex h-2 w-2 rounded-full bg-amber-500"})]);
                                }), 128)), __props.expiring_soon.length === 0 ? (openBlock(), createBlock("div", {
                                    key: 0,
                                    class: "px-6 py-12 text-center text-sm text-zinc-500"
                                }, " No documents expiring soon. ")) : createCommentVNode("", true)])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`</div><div class="grid gap-6 lg:grid-cols-2"${_scopeId}>`);
                        _push(ssrRenderComponent(_sfc_main$2, {
                            title: "Missing expiry date",
                            padding: "p-0"
                        }, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="divide-y divide-zinc-100"${_scopeId}><!--[-->`);
                                    ssrRenderList(__props.missing_expiry, (document) => {
                                        _push(`<div class="flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"${_scopeId}><p class="text-sm font-semibold text-zinc-900"${_scopeId}>${ssrInterpolate(document.title)}</p><span class="text-xs text-zinc-500"${_scopeId}>${ssrInterpolate(document.status)}</span></div>`);
                                    });
                                    _push(`<!--]-->`);
                                    if (__props.missing_expiry.length === 0) _push(`<div class="px-6 py-12 text-center text-sm text-zinc-500"${_scopeId}> All documents have expiry dates. </div>`);
                                    else _push(`<!---->`);
                                    _push(`</div>`);
                                } else return [createVNode("div", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.missing_expiry, (document) => {
                                    return openBlock(), createBlock("div", {
                                        key: document.id,
                                        class: "flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"
                                    }, [createVNode("p", {class: "text-sm font-semibold text-zinc-900"}, toDisplayString(document.title), 1), createVNode("span", {class: "text-xs text-zinc-500"}, toDisplayString(document.status), 1)]);
                                }), 128)), __props.missing_expiry.length === 0 ? (openBlock(), createBlock("div", {
                                    key: 0,
                                    class: "px-6 py-12 text-center text-sm text-zinc-500"
                                }, " All documents have expiry dates. ")) : createCommentVNode("", true)])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(ssrRenderComponent(_sfc_main$2, {
                            title: "Documents by category",
                            padding: "p-0"
                        }, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="divide-y divide-zinc-100"${_scopeId}><!--[-->`);
                                    ssrRenderList(__props.documents_by_category, (group) => {
                                        _push(`<div class="flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"${_scopeId}><p class="text-sm font-semibold text-zinc-900"${_scopeId}>${ssrInterpolate(group.category_name)}</p><span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-zinc-100 text-xs font-bold text-zinc-900"${_scopeId}>${ssrInterpolate(group.total)}</span></div>`);
                                    });
                                    _push(`<!--]-->`);
                                    if (__props.documents_by_category.length === 0) _push(`<div class="px-6 py-12 text-center text-sm text-zinc-500"${_scopeId}> No documents yet. </div>`);
                                    else _push(`<!---->`);
                                    _push(`</div>`);
                                } else return [createVNode("div", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.documents_by_category, (group) => {
                                    return openBlock(), createBlock("div", {
                                        key: group.category_name,
                                        class: "flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"
                                    }, [createVNode("p", {class: "text-sm font-semibold text-zinc-900"}, toDisplayString(group.category_name), 1), createVNode("span", {class: "inline-flex h-6 w-6 items-center justify-center rounded-full bg-zinc-100 text-xs font-bold text-zinc-900"}, toDisplayString(group.total), 1)]);
                                }), 128)), __props.documents_by_category.length === 0 ? (openBlock(), createBlock("div", {
                                    key: 0,
                                    class: "px-6 py-12 text-center text-sm text-zinc-500"
                                }, " No documents yet. ")) : createCommentVNode("", true)])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`</div></div>`);
                    } else return [createVNode("div", {class: "space-y-8"}, [
                        createVNode("header", null, [createVNode("h1", {class: "text-3xl font-bold tracking-tight text-zinc-900"}, "Dashboard"), createVNode("p", {class: "mt-1 text-sm text-zinc-500"}, "Overview of your document library and upcoming expirations.")]),
                        createVNode("section", {class: "grid gap-6 sm:grid-cols-2 lg:grid-cols-3"}, [
                            createVNode(_sfc_main$2, {padding: "px-6 py-6"}, {
                                default: withCtx(() => [createVNode("div", {class: "flex items-center gap-4"}, [createVNode("div", {class: "flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-900"}, [createVNode(unref(DocumentTextIcon), {class: "h-6 w-6"})]), createVNode("div", null, [createVNode("p", {class: "text-sm font-medium text-zinc-500"}, "Total documents"), createVNode("p", {class: "text-3xl font-bold text-zinc-900"}, toDisplayString(__props.stats.total_documents), 1)])])]),
                                _: 1
                            }),
                            createVNode(_sfc_main$2, {padding: "px-6 py-6"}, {
                                default: withCtx(() => [createVNode("div", {class: "flex items-center gap-4"}, [createVNode("div", {class: "flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600"}, [createVNode(unref(CalendarIcon), {class: "h-6 w-6"})]), createVNode("div", null, [createVNode("p", {class: "text-sm font-medium text-zinc-500"}, "Expiring in 30 days"), createVNode("p", {class: "text-3xl font-bold text-zinc-900"}, toDisplayString(__props.stats.expiring_soon_count), 1)])])]),
                                _: 1
                            }),
                            createVNode(_sfc_main$2, {padding: "px-6 py-6"}, {
                                default: withCtx(() => [createVNode("div", {class: "flex items-center gap-4"}, [createVNode("div", {class: "flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600"}, [createVNode(unref(ExclamationCircleIcon), {class: "h-6 w-6"})]), createVNode("div", null, [createVNode("p", {class: "text-sm font-medium text-zinc-500"}, "Missing expiry date"), createVNode("p", {class: "text-3xl font-bold text-zinc-900"}, toDisplayString(__props.stats.missing_expiry_count), 1)])])]),
                                _: 1
                            })
                        ]),
                        createVNode("div", {class: "grid gap-6 lg:grid-cols-2"}, [createVNode(_sfc_main$2, {
                            title: "Recent uploads",
                            padding: "p-0"
                        }, {
                            footer: withCtx(() => [createVNode(unref(Link), {
                                href: "/documents",
                                class: "text-xs font-semibold text-zinc-900 hover:underline"
                            }, {
                                default: withCtx(() => [createTextVNode("View all documents →")]),
                                _: 1
                            })]),
                            default: withCtx(() => [createVNode("div", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.recent_uploads, (document) => {
                                return openBlock(), createBlock("div", {
                                    key: document.id,
                                    class: "flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"
                                }, [createVNode("div", null, [createVNode("p", {class: "text-sm font-semibold text-zinc-900"}, toDisplayString(document.title), 1), createVNode("p", {class: "text-xs text-zinc-500"}, toDisplayString(document.updated_at), 1)]), createVNode("span", {class: "rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-zinc-600"}, toDisplayString(document.status), 1)]);
                            }), 128)), __props.recent_uploads.length === 0 ? (openBlock(), createBlock("div", {
                                key: 0,
                                class: "px-6 py-12 text-center text-sm text-zinc-500"
                            }, " No uploads yet. ")) : createCommentVNode("", true)])]),
                            _: 1
                        }), createVNode(_sfc_main$2, {
                            title: "Expiring soon",
                            padding: "p-0"
                        }, {
                            default: withCtx(() => [createVNode("div", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.expiring_soon, (document) => {
                                return openBlock(), createBlock("div", {
                                    key: document.id,
                                    class: "flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"
                                }, [createVNode("div", null, [createVNode("p", {class: "text-sm font-semibold text-zinc-900"}, toDisplayString(document.title), 1), createVNode("p", {class: "text-xs text-zinc-500"}, "Expires on " + toDisplayString(document.expiry_date), 1)]), createVNode("div", {class: "flex h-2 w-2 rounded-full bg-amber-500"})]);
                            }), 128)), __props.expiring_soon.length === 0 ? (openBlock(), createBlock("div", {
                                key: 0,
                                class: "px-6 py-12 text-center text-sm text-zinc-500"
                            }, " No documents expiring soon. ")) : createCommentVNode("", true)])]),
                            _: 1
                        })]),
                        createVNode("div", {class: "grid gap-6 lg:grid-cols-2"}, [createVNode(_sfc_main$2, {
                            title: "Missing expiry date",
                            padding: "p-0"
                        }, {
                            default: withCtx(() => [createVNode("div", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.missing_expiry, (document) => {
                                return openBlock(), createBlock("div", {
                                    key: document.id,
                                    class: "flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"
                                }, [createVNode("p", {class: "text-sm font-semibold text-zinc-900"}, toDisplayString(document.title), 1), createVNode("span", {class: "text-xs text-zinc-500"}, toDisplayString(document.status), 1)]);
                            }), 128)), __props.missing_expiry.length === 0 ? (openBlock(), createBlock("div", {
                                key: 0,
                                class: "px-6 py-12 text-center text-sm text-zinc-500"
                            }, " All documents have expiry dates. ")) : createCommentVNode("", true)])]),
                            _: 1
                        }), createVNode(_sfc_main$2, {
                            title: "Documents by category",
                            padding: "p-0"
                        }, {
                            default: withCtx(() => [createVNode("div", {class: "divide-y divide-zinc-100"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.documents_by_category, (group) => {
                                return openBlock(), createBlock("div", {
                                    key: group.category_name,
                                    class: "flex items-center justify-between px-6 py-4 hover:bg-zinc-50/50"
                                }, [createVNode("p", {class: "text-sm font-semibold text-zinc-900"}, toDisplayString(group.category_name), 1), createVNode("span", {class: "inline-flex h-6 w-6 items-center justify-center rounded-full bg-zinc-100 text-xs font-bold text-zinc-900"}, toDisplayString(group.total), 1)]);
                            }), 128)), __props.documents_by_category.length === 0 ? (openBlock(), createBlock("div", {
                                key: 0,
                                class: "px-6 py-12 text-center text-sm text-zinc-500"
                            }, " No documents yet. ")) : createCommentVNode("", true)])]),
                            _: 1
                        })])
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
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Dashboard.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main as default};

//# sourceMappingURL=Dashboard--942u0xm.js.map
