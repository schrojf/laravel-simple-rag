import './bootstrap';

const pages = {
    dashboardPage: () => import('./pages/dashboard'),
    adminInvitationCodesPage: () => import('./pages/admin/invitation-codes'),
    entriesEditorPage: () => import('./pages/entries/editor'),
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

    // Here will be code which is always loaded e.g. menu bar toggle etc.
}

if (document.readyState !== 'loading') {
    ready();
} else {
    document.addEventListener('DOMContentLoaded', ready);
}
