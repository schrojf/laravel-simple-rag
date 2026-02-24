let pendingForm: HTMLFormElement | null = null;
let overlay: HTMLElement | null = null;

function getOverlay(): HTMLElement {
    if (overlay) return overlay;

    overlay = document.createElement('div');
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-modal', 'true');
    overlay.setAttribute('aria-labelledby', 'confirmModalTitle');
    overlay.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm hidden';
    overlay.innerHTML = `
        <div class="bg-white rounded-xl shadow-xl w-full max-w-sm">
            <div class="px-6 pt-6 pb-2">
                <h3 id="confirmModalTitle" class="text-base font-semibold text-zinc-900"></h3>
            </div>
            <div class="px-6 py-4 flex justify-end gap-3">
                <button id="confirmModalCancel" type="button"
                        class="px-4 py-2 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 rounded-lg hover:bg-zinc-50 transition-colors cursor-pointer">
                    Cancel
                </button>
                <button id="confirmModalConfirm" type="button"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors cursor-pointer">
                    Confirm
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);

    overlay.querySelector('#confirmModalCancel')!.addEventListener('click', close);
    overlay.querySelector('#confirmModalConfirm')!.addEventListener('click', confirm);

    overlay.addEventListener('click', (e: MouseEvent) => {
        if (e.target === overlay) close();
    });

    document.addEventListener('keydown', (e: KeyboardEvent) => {
        if (overlay!.classList.contains('hidden')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'Enter') confirm();
    });

    return overlay;
}

function open(form: HTMLFormElement, message: string): void {
    pendingForm = form;
    const el = getOverlay();
    el.querySelector<HTMLElement>('#confirmModalTitle')!.textContent = message;
    el.classList.remove('hidden');
    el.querySelector<HTMLButtonElement>('#confirmModalCancel')!.focus();
}

function close(): void {
    pendingForm = null;
    getOverlay().classList.add('hidden');
}

function confirm(): void {
    if (!pendingForm) return;
    const form = pendingForm;
    close();
    form.submit();
}

export function initConfirmModal(): void {
    document.addEventListener('submit', (event: Event) => {
        const form = event.target as HTMLFormElement;
        const message = form.dataset.confirm;
        if (!message) return;
        event.preventDefault();
        open(form, message);
    });
}
