import './bootstrap';

const pages = {
    dashboardPage: () => import('./pages/dashboard'),
    adminInvitationCodesPage: () => import('./pages/admin/invitation-codes'),
    entriesEditorPage: () => import('./pages/entries/editor'),
    twoFactorPage: () => import('./pages/settings/two-factor'),
    // Add more pages here...
};

function ready() {
    Object.entries(pages).forEach(([id, loader]) => {
        const el = document.getElementById(id);
        if (el) {
            loader()
                .then((script) => script.init(el))
                .catch((err) => console.error(`Failed to load ${id}:`, err));
        }
    });

    // Confirm dialog for any form with a data-confirm attribute
    document.addEventListener('submit', (event: Event) => {
        const form = event.target as HTMLFormElement;
        const message = form.dataset.confirm;
        if (message && !window.confirm(message)) {
            event.preventDefault();
        }
    });

    // User menu dropdown
    const userMenuButton = document.getElementById('userMenuButton') as HTMLButtonElement | null;
    const userMenuDropdown = document.getElementById('userMenuDropdown') as HTMLElement | null;

    if (userMenuButton && userMenuDropdown) {
        userMenuButton.addEventListener('click', (e: MouseEvent) => {
            e.stopPropagation();
            const isOpen = !userMenuDropdown.classList.contains('hidden');
            userMenuDropdown.classList.toggle('hidden', isOpen);
            userMenuButton.setAttribute('aria-expanded', String(!isOpen));
        });

        document.addEventListener('click', () => {
            if (!userMenuDropdown.classList.contains('hidden')) {
                userMenuDropdown.classList.add('hidden');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', (e: KeyboardEvent) => {
            if (e.key === 'Escape' && !userMenuDropdown.classList.contains('hidden')) {
                userMenuDropdown.classList.add('hidden');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Character counters for inputs/textareas with maxlength
    function initCharCounters(): void {
        document.querySelectorAll<HTMLInputElement | HTMLTextAreaElement>('input[maxlength], textarea[maxlength]').forEach((input) => {
            const max = input.maxLength;
            if (max <= 0) return;

            const counter = document.createElement('span');
            counter.className = 'block text-xs text-zinc-400 mt-1 text-right';

            const update = (): void => {
                const len = input.value.length;
                counter.textContent = `${len} / ${max}`;
                const nearLimit = len > max * 0.9;
                counter.classList.toggle('text-amber-500', nearLimit);
                counter.classList.toggle('text-zinc-400', !nearLimit);
            };

            input.insertAdjacentElement('afterend', counter);
            input.addEventListener('input', update);
            update();
        });
    }

    initCharCounters();

    // Navigation scroll gradients
    const navScrollArea = document.getElementById('navScrollArea') as HTMLElement | null;
    const navFadeLeft = document.getElementById('navFadeLeft') as HTMLElement | null;
    const navFadeRight = document.getElementById('navFadeRight') as HTMLElement | null;

    if (navScrollArea && navFadeLeft && navFadeRight) {
        const updateFades = (): void => {
            const { scrollLeft, scrollWidth, clientWidth } = navScrollArea;
            navFadeLeft.classList.toggle('hidden', scrollLeft <= 0);
            navFadeRight.classList.toggle('hidden', scrollLeft + clientWidth >= scrollWidth - 1);
        };

        navScrollArea.addEventListener('scroll', updateFades, { passive: true });
        updateFades();
    }
}

if (document.readyState !== 'loading') {
    ready();
} else {
    document.addEventListener('DOMContentLoaded', ready);
}
