import MarkdownIt from 'markdown-it';
import { initKeyValueEditor } from '../../components/key-value-editor';

const md = new MarkdownIt({ html: false, linkify: true, typographer: true });

function updateTokenCount(content: string, tokenEl: HTMLElement): void {
    const tokens = Math.ceil(content.length / 4);
    tokenEl.textContent = `~${tokens.toLocaleString()} tokens`;
}

function renderPreview(content: string, previewEl: HTMLElement): void {
    if (content.trim() === '') {
        previewEl.innerHTML = '<p class="text-zinc-400 italic">Preview will appear here…</p>';
    } else {
        previewEl.innerHTML = md.render(content);
    }
}

export function init(el: HTMLElement): void {
    const textarea = el.querySelector<HTMLTextAreaElement>('textarea#content');
    const previewEl = el.querySelector<HTMLElement>('#markdownPreview');
    const tokenEl = el.querySelector<HTMLElement>('#tokenCount');

    if (!textarea || !previewEl || !tokenEl) {
        return;
    }

    const update = (): void => {
        const content = textarea.value;
        renderPreview(content, previewEl);
        updateTokenCount(content, tokenEl);
    };

    textarea.addEventListener('input', update);

    // Run immediately to handle pre-filled content (edit page)
    update();

    // Key-value editor for any meta textarea
    el.querySelectorAll<HTMLTextAreaElement>('textarea[data-kv-editor]').forEach((metaTextarea) => {
        initKeyValueEditor(metaTextarea);
    });
}
