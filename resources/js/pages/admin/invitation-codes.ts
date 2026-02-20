const MAX_LENGTH = 500;

export function init(el: HTMLElement) {
    const textarea = el.querySelector('textarea[name="description"]') as HTMLTextAreaElement | null;
    if (!textarea) {
        return;
    }

    const counter = document.createElement('p');
    counter.className = 'text-xs mt-1 text-zinc-400';
    textarea.after(counter);

    const errorMsg = document.createElement('p');
    errorMsg.className = 'text-sm mt-1 text-red-600 hidden';
    errorMsg.textContent = `Description must not exceed ${MAX_LENGTH} characters.`;
    counter.after(errorMsg);

    const submitBtn = el.querySelector('button[type="submit"]') as HTMLButtonElement | null;

    const update = () => {
        const len = textarea.value.length;
        const over = len > MAX_LENGTH;

        counter.textContent = `${len} / ${MAX_LENGTH}`;
        counter.className = `text-xs mt-1 ${over ? 'text-red-500 font-medium' : 'text-zinc-400'}`;
        errorMsg.classList.toggle('hidden', !over);

        if (submitBtn) {
            submitBtn.disabled = over;
            submitBtn.classList.toggle('opacity-50', over);
            submitBtn.classList.toggle('cursor-not-allowed', over);
        }
    };

    textarea.addEventListener('input', update);
    update();
}
