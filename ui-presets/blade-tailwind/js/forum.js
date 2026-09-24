import feather from 'feather-icons';
import { createApp, ref, reactive, watch, computed } from 'vue/dist/vue.esm-bundler.js';
import axios from 'axios';
import Pickr from '@simonwep/pickr';
import draggable from 'vuedraggable/src/vuedraggable';

import '@simonwep/pickr/dist/themes/classic.min.css';

window.axios = axios;
window.Vue = { createApp, ref, reactive, watch, computed };
window.VueDraggable = draggable;

function deepMerge(target, source) {
    const result = { ...target };

    for (const [key, value] of Object.entries(source ?? {})) {
        const isPlainObject = value !== null && typeof value === 'object' && !Array.isArray(value);
        const canMerge = isPlainObject && typeof result[key] === 'object' && result[key] !== null && !Array.isArray(result[key]);

        result[key] = canMerge ? deepMerge(result[key], value) : value;
    }

    return result;
}

function resolveEditorHandlers(options) {
    const handlers = options?.modules?.toolbar?.handlers;

    if (!handlers) return options;

    for (const [name, handler] of Object.entries(handlers)) {
        if (typeof handler !== 'string') continue;

        handlers[name] = function (...args) {
            const callback = window[handler];

            if (typeof callback === 'function') {
                return callback.apply(this, args);
            }

            console.warn(`[forum] The toolbar handler "${handler}" is not defined.`);
        };
    }

    return options;
}

function parseEditorOptions(element) {
    let parsed = {};

    const raw = element?.dataset?.forumEditorOptions;

    if (raw) {
        try {
            parsed = JSON.parse(raw);
        } catch (error) {
            console.warn('[forum] Ignoring an invalid data-forum-editor-options value.', error);
        }
    }

    return resolveEditorHandlers(deepMerge({ theme: 'snow' }, parsed));
}

document.addEventListener('DOMContentLoaded', function () {
    createApp({
        setup() {
            const isCollapsed = ref(true);
            const isManageDropdownCollapsed = ref(true);
            const isUserDropdownCollapsed = ref(true);

            window.addEventListener('click', event => {
                const ignore = ['navbar-toggler', 'navbar-toggler-icon', 'dropdown-toggle'];
                if (ignore.some(className => event.target.classList.contains(className))) return;
                if (!isCollapsed.value) isCollapsed.value = true;
                if (!isManageDropdownCollapsed.value) isManageDropdownCollapsed.value = true;
                if (!isUserDropdownCollapsed.value) isUserDropdownCollapsed.value = true;
            });

            return {
                isCollapsed,
                isManageDropdownCollapsed,
                isUserDropdownCollapsed,
            };
        }
    }).mount('.v-navbar');

    const pendingApprovalElement = document.getElementById('pending-approval');
    if (pendingApprovalElement) {
        createApp({
            setup() {
                const allIds = JSON.parse(pendingApprovalElement.dataset.allIds || '[]');
                const selectedIds = ref([]);

                const selectAll = computed({
                    get: () => allIds.length > 0 && selectedIds.value.length === allIds.length,
                    set: (val) => {
                        selectedIds.value = val ? [...allIds] : [];
                    }
                });

                return {
                    selectedIds,
                    selectAll
                };
            }
        }).mount(pendingApprovalElement);
    }

    function findModal(key) {
        const modal = document.querySelector(`[data-modal=${key}]`);

        if (!modal) throw `Attempted to open modal '${key}' but no such modal found.`;

        return modal;
    }

    function openModal(modal) {
        setTimeout(function () {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }, 200);
    }

    function closeModal(modal) {
        setTimeout(function () {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 200);
    }

    document.addEventListener('click', event => {
        // Open Modal
        const openTrigger = event.target.closest('[data-open-modal]');
        if (openTrigger) {
            event.preventDefault();
            openModal(findModal(openTrigger.dataset.openModal));
            return;
        }

        // Close Modal
        const closeTrigger = event.target.closest('[data-close-modal]');
        if (closeTrigger) {
            event.preventDefault();
            const modal = closeTrigger.closest('[data-modal]');
            if (modal) closeModal(modal);
            return;
        }

        // Dismiss Alert
        const dismissTrigger = event.target.closest('[data-dismiss]');
        if (dismissTrigger) {
            const target = dismissTrigger.parentElement;
            if (target) target.style.display = 'none';
        }
    });

    const hash = window.location.hash.substr(1);
    if (hash.startsWith('modal=')) {
        openModal(findModal(hash.replace('modal=', '')));
    }

    feather.replace();

    function initializeQuill(textarea) {
        const container = document.createElement('div');
        textarea.parentNode.insertBefore(container, textarea.nextSibling);
        textarea.style.display = 'none';

        const quill = new window.Quill(container, parseEditorOptions(textarea));

        if (textarea.value) {
            quill.clipboard.dangerouslyPasteHTML(textarea.value);
        }

        const sync = () => {
            textarea.value = quill.root.innerHTML;
        };

        quill.on('text-change', sync);

        const form = textarea.closest('form');
        if (form) {
            form.addEventListener('submit', sync);
        }
    }

    document.querySelectorAll('[data-forum-editor="quill"]').forEach(textarea => {
        if (typeof window.Quill === 'undefined') return;
        initializeQuill(textarea);
    });

    const input = document.querySelector('input[name=color_light_mode]');

    if (!input) return;

    const pickr = Pickr.create({
        el: '.pickr',
        theme: 'classic',
        default: input.value || null,

        swatches: [
            window.defaultCategoryColor,
            '#f44336',
            '#e91e63',
            '#9c27b0',
            '#673ab7',
            '#3f51b5',
            '#2196f3',
            '#03a9f4',
            '#00bcd4',
            '#009688',
            '#4caf50',
            '#8bc34a',
            '#cddc39',
            '#ffeb3b',
            '#ffc107'
        ],

        components: {
            preview: true,
            hue: true,
            interaction: {
                input: true,
                save: true
            }
        },

        strings: {
            save: 'Apply'
        }
    });

    pickr.on('save', instance => pickr.hide())
        .on('clear', instance => {
            input.value = '';
            input.dispatchEvent(new Event('change'));
        })
        .on('cancel', instance => {
            const selectedColor = instance
                .getSelectedColor()
                .toHEXA()
                .toString();

            input.value = selectedColor;
            input.dispatchEvent(new Event('change'));
        })
        .on('change', (color, instance) => {
            const selectedColor = color
                .toHEXA()
                .toString();

            input.value = selectedColor;
            input.dispatchEvent(new Event('change'));
        });
});
