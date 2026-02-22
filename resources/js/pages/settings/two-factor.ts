export function init(el: HTMLElement): void {
    initCopySetupKey(el);
    initRecoveryCodesToggle(el);
}

function initCopySetupKey(el: HTMLElement): void {
    const btn = el.querySelector<HTMLButtonElement>('#copySetupKeyBtn');
    if (!btn) return;

    const input = el.querySelector<HTMLInputElement>('#setupKeyInput');
    const copyIcon = el.querySelector<HTMLElement>('#copyIcon');
    const copiedIcon = el.querySelector<HTMLElement>('#copiedIcon');

    btn.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(input?.value ?? '');
            copyIcon?.classList.add('hidden');
            copiedIcon?.classList.remove('hidden');
            setTimeout(() => {
                copyIcon?.classList.remove('hidden');
                copiedIcon?.classList.add('hidden');
            }, 1500);
        } catch {
            console.warn('Could not copy to clipboard');
        }
    });
}

function initRecoveryCodesToggle(el: HTMLElement): void {
    const viewBtn = el.querySelector<HTMLButtonElement>('#viewRecoveryCodes');
    const hideBtn = el.querySelector<HTMLButtonElement>('#hideRecoveryCodes');
    const codesSection = el.querySelector<HTMLElement>('#recovery-codes-section');
    const regenerateForm = el.querySelector<HTMLElement>('#regenerateCodesForm');

    if (!viewBtn || !hideBtn) return;

    viewBtn.addEventListener('click', () => {
        viewBtn.classList.add('hidden');
        hideBtn.classList.remove('hidden');
        codesSection?.classList.remove('hidden');
        regenerateForm?.classList.remove('hidden');
    });

    hideBtn.addEventListener('click', () => {
        hideBtn.classList.add('hidden');
        viewBtn.classList.remove('hidden');
        codesSection?.classList.add('hidden');
        regenerateForm?.classList.add('hidden');
    });
}
