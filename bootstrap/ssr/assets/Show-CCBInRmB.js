import {n as _sfc_main$1, t as _sfc_main$2} from "./Card-Cxv5bgu1.js";
import {t as _sfc_main$3} from "./Button-BKBm8czA.js";
import {t as _sfc_main$4} from "./Badge-DXJPokTZ.js";
import {Head, router} from "@inertiajs/vue3";
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
import {ssrInterpolate, ssrRenderAttr, ssrRenderComponent, ssrRenderList} from "vue/server-renderer";
import {ArchiveBoxIcon, ArrowDownTrayIcon, ArrowPathIcon, PencilIcon} from "@heroicons/vue/24/outline";
//#region resources/js/Pages/Documents/Show.vue
var _sfc_main = {
    __name: "Show",
    __ssrInlineRender: true,
    props: {
        document: Object,
        previewUrl: String,
        downloadUrl: String
    },
    setup(__props) {
        const props = __props;
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
        const archive = () => {
            router.post(`/documents/${props.document.id}/archive`);
        };
        const restore = () => {
            router.post(`/documents/${props.document.id}/restore`);
        };
        return (_ctx, _push, _parent, _attrs) => {
            _push(`<!--[-->`);
            _push(ssrRenderComponent(unref(Head), {title: __props.document.title}, null, _parent));
            _push(ssrRenderComponent(_sfc_main$1, null, {
                default: withCtx((_, _push, _parent, _scopeId) => {
                    if (_push) {
                        _push(`<div class="space-y-6"${_scopeId}><header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"${_scopeId}><div${_scopeId}><div class="flex items-center gap-3"${_scopeId}><h1 class="text-3xl font-bold tracking-tight text-zinc-900"${_scopeId}>${ssrInterpolate(__props.document.title)}</h1>`);
                        _push(ssrRenderComponent(_sfc_main$4, {variant: getStatusVariant(__props.document.status)}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) _push(`${ssrInterpolate(__props.document.status)}`);
                                else return [createTextVNode(toDisplayString(__props.document.status), 1)];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`</div><p class="mt-1 text-sm text-zinc-500"${_scopeId}> Uploaded by ${ssrInterpolate(__props.document.user?.name || "you")} • Last updated ${ssrInterpolate(__props.document.updated_at)}</p></div><div class="flex items-center gap-2"${_scopeId}>`);
                        _push(ssrRenderComponent(_sfc_main$3, {
                            href: `/documents/${__props.document.id}/edit`,
                            variant: "secondary"
                        }, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(ssrRenderComponent(unref(PencilIcon), {class: "mr-2 h-4 w-4"}, null, _parent, _scopeId));
                                    _push(` Edit `);
                                } else return [createVNode(unref(PencilIcon), {class: "mr-2 h-4 w-4"}), createTextVNode(" Edit ")];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        if (__props.document.status === "archived") _push(ssrRenderComponent(_sfc_main$3, {
                            variant: "secondary",
                            onClick: restore
                        }, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(ssrRenderComponent(unref(ArrowPathIcon), {class: "mr-2 h-4 w-4"}, null, _parent, _scopeId));
                                    _push(` Restore `);
                                } else return [createVNode(unref(ArrowPathIcon), {class: "mr-2 h-4 w-4"}), createTextVNode(" Restore ")];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        else _push(ssrRenderComponent(_sfc_main$3, {
                            variant: "secondary",
                            onClick: archive
                        }, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(ssrRenderComponent(unref(ArchiveBoxIcon), {class: "mr-2 h-4 w-4"}, null, _parent, _scopeId));
                                    _push(` Archive `);
                                } else return [createVNode(unref(ArchiveBoxIcon), {class: "mr-2 h-4 w-4"}), createTextVNode(" Archive ")];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(ssrRenderComponent(_sfc_main$3, {
                            href: __props.downloadUrl,
                            as: "a",
                            target: "_blank"
                        }, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(ssrRenderComponent(unref(ArrowDownTrayIcon), {class: "mr-2 h-4 w-4"}, null, _parent, _scopeId));
                                    _push(` Download `);
                                } else return [createVNode(unref(ArrowDownTrayIcon), {class: "mr-2 h-4 w-4"}), createTextVNode(" Download ")];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`</div></header><div class="grid gap-6 lg:grid-cols-3"${_scopeId}><div class="space-y-6 lg:col-span-2"${_scopeId}>`);
                        _push(ssrRenderComponent(_sfc_main$2, {padding: "p-0"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) _push(`<div class="aspect-4/3 w-full overflow-hidden bg-zinc-100 sm:aspect-auto sm:h-[70vh]"${_scopeId}><iframe${ssrRenderAttr("src", __props.previewUrl)} class="h-full w-full border-none" title="Document preview"${_scopeId}></iframe></div>`);
                                else return [createVNode("div", {class: "aspect-4/3 w-full overflow-hidden bg-zinc-100 sm:aspect-auto sm:h-[70vh]"}, [createVNode("iframe", {
                                    src: __props.previewUrl,
                                    class: "h-full w-full border-none",
                                    title: "Document preview"
                                }, null, 8, ["src"])])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(`</div><div class="space-y-6"${_scopeId}>`);
                        _push(ssrRenderComponent(_sfc_main$2, {title: "Document Details"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) _push(`<dl class="space-y-4 text-sm"${_scopeId}><div${_scopeId}><dt class="font-medium text-zinc-500"${_scopeId}>Category</dt><dd class="mt-1 font-semibold text-zinc-900"${_scopeId}>${ssrInterpolate(__props.document.category?.name ?? "Uncategorized")}</dd></div><div${_scopeId}><dt class="font-medium text-zinc-500"${_scopeId}>Original Filename</dt><dd class="mt-1 font-mono text-xs text-zinc-900"${_scopeId}>${ssrInterpolate(__props.document.original_filename)}</dd></div><div class="grid grid-cols-2 gap-4 border-t border-zinc-100 pt-4"${_scopeId}><div${_scopeId}><dt class="font-medium text-zinc-500"${_scopeId}>Issue Date</dt><dd class="mt-1 font-semibold text-zinc-900"${_scopeId}>${ssrInterpolate(__props.document.issue_date ?? "—")}</dd></div><div${_scopeId}><dt class="font-medium text-zinc-500"${_scopeId}>Expiry Date</dt><dd class="mt-1 font-semibold text-zinc-900"${_scopeId}>${ssrInterpolate(__props.document.expiry_date ?? "—")}</dd></div></div></dl>`);
                                else return [createVNode("dl", {class: "space-y-4 text-sm"}, [
                                    createVNode("div", null, [createVNode("dt", {class: "font-medium text-zinc-500"}, "Category"), createVNode("dd", {class: "mt-1 font-semibold text-zinc-900"}, toDisplayString(__props.document.category?.name ?? "Uncategorized"), 1)]),
                                    createVNode("div", null, [createVNode("dt", {class: "font-medium text-zinc-500"}, "Original Filename"), createVNode("dd", {class: "mt-1 font-mono text-xs text-zinc-900"}, toDisplayString(__props.document.original_filename), 1)]),
                                    createVNode("div", {class: "grid grid-cols-2 gap-4 border-t border-zinc-100 pt-4"}, [createVNode("div", null, [createVNode("dt", {class: "font-medium text-zinc-500"}, "Issue Date"), createVNode("dd", {class: "mt-1 font-semibold text-zinc-900"}, toDisplayString(__props.document.issue_date ?? "—"), 1)]), createVNode("div", null, [createVNode("dt", {class: "font-medium text-zinc-500"}, "Expiry Date"), createVNode("dd", {class: "mt-1 font-semibold text-zinc-900"}, toDisplayString(__props.document.expiry_date ?? "—"), 1)])])
                                ])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        _push(ssrRenderComponent(_sfc_main$2, {title: "Notes"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) _push(`<p class="whitespace-pre-wrap text-sm leading-relaxed text-zinc-600"${_scopeId}>${ssrInterpolate(__props.document.notes || "No notes provided for this document.")}</p>`);
                                else return [createVNode("p", {class: "whitespace-pre-wrap text-sm leading-relaxed text-zinc-600"}, toDisplayString(__props.document.notes || "No notes provided for this document."), 1)];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        if (__props.document.tags?.length) _push(ssrRenderComponent(_sfc_main$2, {title: "Tags"}, {
                            default: withCtx((_, _push, _parent, _scopeId) => {
                                if (_push) {
                                    _push(`<div class="flex flex-wrap gap-2"${_scopeId}><!--[-->`);
                                    ssrRenderList(__props.document.tags, (tag) => {
                                        _push(ssrRenderComponent(_sfc_main$4, {
                                            key: tag.id,
                                            variant: "info"
                                        }, {
                                            default: withCtx((_, _push, _parent, _scopeId) => {
                                                if (_push) _push(`${ssrInterpolate(tag.name)}`);
                                                else return [createTextVNode(toDisplayString(tag.name), 1)];
                                            }),
                                            _: 2
                                        }, _parent, _scopeId));
                                    });
                                    _push(`<!--]--></div>`);
                                } else return [createVNode("div", {class: "flex flex-wrap gap-2"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.document.tags, (tag) => {
                                    return openBlock(), createBlock(_sfc_main$4, {
                                        key: tag.id,
                                        variant: "info"
                                    }, {
                                        default: withCtx(() => [createTextVNode(toDisplayString(tag.name), 1)]),
                                        _: 2
                                    }, 1024);
                                }), 128))])];
                            }),
                            _: 1
                        }, _parent, _scopeId));
                        else _push(`<!---->`);
                        _push(`</div></div></div>`);
                    } else return [createVNode("div", {class: "space-y-6"}, [createVNode("header", {class: "flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"}, [createVNode("div", null, [createVNode("div", {class: "flex items-center gap-3"}, [createVNode("h1", {class: "text-3xl font-bold tracking-tight text-zinc-900"}, toDisplayString(__props.document.title), 1), createVNode(_sfc_main$4, {variant: getStatusVariant(__props.document.status)}, {
                        default: withCtx(() => [createTextVNode(toDisplayString(__props.document.status), 1)]),
                        _: 1
                    }, 8, ["variant"])]), createVNode("p", {class: "mt-1 text-sm text-zinc-500"}, " Uploaded by " + toDisplayString(__props.document.user?.name || "you") + " • Last updated " + toDisplayString(__props.document.updated_at), 1)]), createVNode("div", {class: "flex items-center gap-2"}, [
                        createVNode(_sfc_main$3, {
                            href: `/documents/${__props.document.id}/edit`,
                            variant: "secondary"
                        }, {
                            default: withCtx(() => [createVNode(unref(PencilIcon), {class: "mr-2 h-4 w-4"}), createTextVNode(" Edit ")]),
                            _: 1
                        }, 8, ["href"]),
                        __props.document.status === "archived" ? (openBlock(), createBlock(_sfc_main$3, {
                            key: 0,
                            variant: "secondary",
                            onClick: restore
                        }, {
                            default: withCtx(() => [createVNode(unref(ArrowPathIcon), {class: "mr-2 h-4 w-4"}), createTextVNode(" Restore ")]),
                            _: 1
                        })) : (openBlock(), createBlock(_sfc_main$3, {
                            key: 1,
                            variant: "secondary",
                            onClick: archive
                        }, {
                            default: withCtx(() => [createVNode(unref(ArchiveBoxIcon), {class: "mr-2 h-4 w-4"}), createTextVNode(" Archive ")]),
                            _: 1
                        })),
                        createVNode(_sfc_main$3, {
                            href: __props.downloadUrl,
                            as: "a",
                            target: "_blank"
                        }, {
                            default: withCtx(() => [createVNode(unref(ArrowDownTrayIcon), {class: "mr-2 h-4 w-4"}), createTextVNode(" Download ")]),
                            _: 1
                        }, 8, ["href"])
                    ])]), createVNode("div", {class: "grid gap-6 lg:grid-cols-3"}, [createVNode("div", {class: "space-y-6 lg:col-span-2"}, [createVNode(_sfc_main$2, {padding: "p-0"}, {
                        default: withCtx(() => [createVNode("div", {class: "aspect-4/3 w-full overflow-hidden bg-zinc-100 sm:aspect-auto sm:h-[70vh]"}, [createVNode("iframe", {
                            src: __props.previewUrl,
                            class: "h-full w-full border-none",
                            title: "Document preview"
                        }, null, 8, ["src"])])]),
                        _: 1
                    })]), createVNode("div", {class: "space-y-6"}, [
                        createVNode(_sfc_main$2, {title: "Document Details"}, {
                            default: withCtx(() => [createVNode("dl", {class: "space-y-4 text-sm"}, [
                                createVNode("div", null, [createVNode("dt", {class: "font-medium text-zinc-500"}, "Category"), createVNode("dd", {class: "mt-1 font-semibold text-zinc-900"}, toDisplayString(__props.document.category?.name ?? "Uncategorized"), 1)]),
                                createVNode("div", null, [createVNode("dt", {class: "font-medium text-zinc-500"}, "Original Filename"), createVNode("dd", {class: "mt-1 font-mono text-xs text-zinc-900"}, toDisplayString(__props.document.original_filename), 1)]),
                                createVNode("div", {class: "grid grid-cols-2 gap-4 border-t border-zinc-100 pt-4"}, [createVNode("div", null, [createVNode("dt", {class: "font-medium text-zinc-500"}, "Issue Date"), createVNode("dd", {class: "mt-1 font-semibold text-zinc-900"}, toDisplayString(__props.document.issue_date ?? "—"), 1)]), createVNode("div", null, [createVNode("dt", {class: "font-medium text-zinc-500"}, "Expiry Date"), createVNode("dd", {class: "mt-1 font-semibold text-zinc-900"}, toDisplayString(__props.document.expiry_date ?? "—"), 1)])])
                            ])]),
                            _: 1
                        }),
                        createVNode(_sfc_main$2, {title: "Notes"}, {
                            default: withCtx(() => [createVNode("p", {class: "whitespace-pre-wrap text-sm leading-relaxed text-zinc-600"}, toDisplayString(__props.document.notes || "No notes provided for this document."), 1)]),
                            _: 1
                        }),
                        __props.document.tags?.length ? (openBlock(), createBlock(_sfc_main$2, {
                            key: 0,
                            title: "Tags"
                        }, {
                            default: withCtx(() => [createVNode("div", {class: "flex flex-wrap gap-2"}, [(openBlock(true), createBlock(Fragment, null, renderList(__props.document.tags, (tag) => {
                                return openBlock(), createBlock(_sfc_main$4, {
                                    key: tag.id,
                                    variant: "info"
                                }, {
                                    default: withCtx(() => [createTextVNode(toDisplayString(tag.name), 1)]),
                                    _: 2
                                }, 1024);
                            }), 128))])]),
                            _: 1
                        })) : createCommentVNode("", true)
                    ])])])];
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
    (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Documents/Show.vue");
    return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
//#endregion
export {_sfc_main as default};

//# sourceMappingURL=Show-CCBInRmB.js.map
