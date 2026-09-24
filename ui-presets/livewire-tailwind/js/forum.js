import { formatDistance } from 'date-fns';
import '@melloware/coloris/dist/coloris.css';
import Coloris from '@melloware/coloris';
import NestedSort from 'nested-sort';

window.dateFormatDistance = formatDistance;
window.Coloris = Coloris;
window.Coloris.init();
window.NestedSort = NestedSort;

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

document.addEventListener('alpine:init', () => {
    window.Alpine.data('forumEditor', (model, driver = 'quill') => ({
        model,
        quill: null,

        init(element) {
            if (driver !== 'quill' || !element) return;

            if (typeof window.Quill === 'undefined') {
                console.warn(`[forum] The "${driver}" editor is configured but window.Quill is unavailable. Falling back to a plain textarea.`);
                return;
            }

            this.quill = new window.Quill(element, parseEditorOptions(this.$el));

            if (this.model) {
                this.quill.clipboard.dangerouslyPasteHTML(this.model);
            }

            this.quill.on('text-change', () => {
                this.model = this.quill.root.innerHTML;
            });

            this.$watch('model', value => {
                if (!this.quill || value === this.quill.root.innerHTML) return;
                this.quill.clipboard.dangerouslyPasteHTML(value || '');
            });
        },
    }));
});
