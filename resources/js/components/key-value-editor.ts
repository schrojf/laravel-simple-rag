interface KvPair {
    key: string;
    value: string;
}

function parseJson(json: string): KvPair[] {
    if (!json.trim()) return [];
    try {
        const parsed: unknown = JSON.parse(json);
        if (typeof parsed !== 'object' || Array.isArray(parsed) || parsed === null) {
            return [];
        }
        return Object.entries(parsed as Record<string, unknown>).map(([key, val]) => ({
            key,
            value: typeof val === 'string' ? val : JSON.stringify(val),
        }));
    } catch {
        return [];
    }
}

function serialize(container: HTMLElement): string {
    const obj: Record<string, string> = {};
    let hasEntries = false;

    container.querySelectorAll<HTMLDivElement>('.kv-row').forEach((row) => {
        const key = row.querySelector<HTMLInputElement>('.kv-key')!.value.trim();
        const value = row.querySelector<HTMLInputElement>('.kv-value')!.value;
        if (key) {
            obj[key] = value;
            hasEntries = true;
        }
    });

    return hasEntries ? JSON.stringify(obj) : '';
}

export function initKeyValueEditor(textarea: HTMLTextAreaElement): void {
    const initialPairs = parseJson(textarea.value);

    textarea.style.display = 'none';

    const container = document.createElement('div');
    container.className = 'space-y-2';

    const rowsContainer = document.createElement('div');
    rowsContainer.className = 'space-y-2';

    const addButton = document.createElement('button');
    addButton.type = 'button';
    addButton.textContent = '+ Add pair';
    addButton.className = 'text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors';

    container.appendChild(rowsContainer);
    container.appendChild(addButton);
    textarea.insertAdjacentElement('afterend', container);

    function syncToTextarea(): void {
        textarea.value = serialize(rowsContainer);
    }

    function addRow(key = '', value = ''): void {
        const row = document.createElement('div');
        row.className = 'kv-row flex items-center gap-2';

        const keyInput = document.createElement('input');
        keyInput.type = 'text';
        keyInput.placeholder = 'key';
        keyInput.value = key;
        keyInput.className =
            'kv-key w-32 shrink-0 border border-zinc-300 rounded-lg px-3 py-1.5 text-sm text-zinc-900 font-mono placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500 focus:outline-none';

        const separator = document.createElement('span');
        separator.textContent = ':';
        separator.className = 'text-zinc-400 text-sm shrink-0';

        const valueInput = document.createElement('input');
        valueInput.type = 'text';
        valueInput.placeholder = 'value';
        valueInput.value = value;
        valueInput.className =
            'kv-value min-w-0 flex-1 border border-zinc-300 rounded-lg px-3 py-1.5 text-sm text-zinc-900 font-mono placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500 focus:outline-none';

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.setAttribute('aria-label', 'Remove pair');
        removeButton.innerHTML = '&times;';
        removeButton.className = 'shrink-0 text-zinc-300 hover:text-red-500 transition-colors text-xl leading-none';

        keyInput.addEventListener('input', syncToTextarea);
        valueInput.addEventListener('input', syncToTextarea);
        removeButton.addEventListener('click', () => {
            row.remove();
            syncToTextarea();
        });

        row.appendChild(keyInput);
        row.appendChild(separator);
        row.appendChild(valueInput);
        row.appendChild(removeButton);

        rowsContainer.appendChild(row);
        syncToTextarea();
    }

    addButton.addEventListener('click', () => addRow());

    for (const { key, value } of initialPairs) {
        addRow(key, value);
    }
}
