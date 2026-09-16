import { formatDistance } from 'date-fns';
import '@melloware/coloris/dist/coloris.css';
import Coloris from '@melloware/coloris';
import NestedSort from 'nested-sort';

window.dateFormatDistance = formatDistance;
window.Coloris = Coloris;
window.Coloris.init();
window.NestedSort = NestedSort;

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

            this.quill = new window.Quill(element, { theme: 'snow' });

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
