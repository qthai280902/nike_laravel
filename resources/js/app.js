import './bootstrap';

const storefrontThemeKey = 'nike-storefront-theme';

function readThemePreference() {
    try {
        return localStorage.getItem(storefrontThemeKey) || 'system';
    } catch (error) {
        return 'system';
    }
}

function writeThemePreference(preference) {
    try {
        localStorage.setItem(storefrontThemeKey, preference);
    } catch (error) {
        // Browsers can block storage in private contexts; the UI can still apply in memory.
    }
}

function resolveTheme(preference) {
    if (preference === 'system') {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    return preference === 'dark' ? 'dark' : 'light';
}

function applyStorefrontTheme(preference) {
    const normalizedPreference = ['light', 'dark', 'system'].includes(preference) ? preference : 'system';
    const resolvedTheme = resolveTheme(normalizedPreference);

    document.documentElement.dataset.theme = resolvedTheme;
    document.documentElement.dataset.themePreference = normalizedPreference;

    document.querySelectorAll('[data-theme-option]').forEach((button) => {
        button.dataset.active = button.dataset.themeOption === normalizedPreference ? 'true' : 'false';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    applyStorefrontTheme(readThemePreference());

    const menu = document.querySelector('[data-theme-menu]');
    const button = document.querySelector('[data-theme-menu-button]');
    const panel = document.querySelector('[data-theme-menu-panel]');

    const setThemeMenuOpen = (isOpen) => {
        panel?.classList.toggle('hidden', ! isOpen);
        button?.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    };

    button?.addEventListener('click', () => {
        setThemeMenuOpen(panel?.classList.contains('hidden') ?? false);
    });

    document.querySelectorAll('[data-theme-option]').forEach((option) => {
        option.addEventListener('click', () => {
            const preference = option.dataset.themeOption || 'system';
            writeThemePreference(preference);
            applyStorefrontTheme(preference);
            setThemeMenuOpen(false);
        });
    });

    document.addEventListener('click', (event) => {
        if (menu && ! menu.contains(event.target)) {
            setThemeMenuOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setThemeMenuOpen(false);
        }
    });

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (readThemePreference() === 'system') {
            applyStorefrontTheme('system');
        }
    });
});
