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
