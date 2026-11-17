/**
 * SiBook Async Container Utility
 * Enables smooth SPA-like container updates for tables, filters, search, and pagination
 * without full-page reloads, preventing lag and reducing resource footprint.
 */

window.SiBookAsync = {
    controllers: {},

    /**
     * Load a URL into a container element by ID via AJAX
     */
    async load(url, containerId, options = {}) {
        const {
            pushState = true,
            preserveSearchFocus = true,
            onSuccess = null
        } = options;

        const container = document.getElementById(containerId);
        if (!container) return;

        // Abort any ongoing request for this container
        if (this.controllers[containerId]) {
            try {
                this.controllers[containerId].abort();
            } catch (e) {}
        }
        const controller = new AbortController();
        this.controllers[containerId] = controller;

        // Subtle non-intrusive loading state (no layout shift, zero lag)
        container.style.transition = 'opacity 150ms ease';
        container.style.opacity = '0.55';
        container.style.pointerEvents = 'none';

        // Check search focus
        const activeEl = document.activeElement;
        const isSearch = preserveSearchFocus && activeEl && activeEl.name === 'search';
        const searchVal = isSearch ? activeEl.value : null;
        const cursorStart = isSearch ? activeEl.selectionStart : null;
        const cursorEnd = isSearch ? activeEl.selectionEnd : null;

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: controller.signal
            });

            if (!response.ok) throw new Error('HTTP ' + response.status);

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById(containerId);

            if (newContainer) {
                container.innerHTML = newContainer.innerHTML;

                // Sync URL in browser address bar
                if (pushState && window.location.href !== url) {
                    window.history.pushState({ asyncContainerId: containerId, url: url }, '', url);
                }

                // Re-initialize Alpine on the new container content
                if (window.Alpine) {
                    window.Alpine.initTree(container);
                }

                // Restore search focus and cursor if applicable
                if (isSearch) {
                    const newSearch = container.querySelector('input[name="search"]');
                    if (newSearch) {
                        newSearch.focus();
                        if (searchVal !== null) newSearch.value = searchVal;
                        if (cursorStart !== null) newSearch.setSelectionRange(cursorStart, cursorEnd);
                    }
                }

                if (typeof onSuccess === 'function') {
                    onSuccess(doc);
                }
            }
        } catch (err) {
            if (err.name !== 'AbortError') {
                console.error('SiBookAsync error:', err);
            }
        } finally {
            container.style.opacity = '1';
            container.style.pointerEvents = '';
            delete this.controllers[containerId];
        }
    },

    /**
     * Intercepts clicks on pagination, sort, and reset links
     */
    handleClick(e, containerId) {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href === '#' || href.startsWith('javascript:')) return;
        if (link.target === '_blank' || link.hasAttribute('download')) return;
        if (link.classList.contains('no-async')) return;

        try {
            const linkUrl = new URL(href, window.location.origin);
            const currentUrl = new URL(window.location.href);

            // Intercept if same pathname (e.g. /admin/logbook)
            if (linkUrl.origin === currentUrl.origin && linkUrl.pathname === currentUrl.pathname) {
                e.preventDefault();
                this.load(href, containerId);
            }
        } catch (err) {}
    },

    /**
     * Handle live search debounced typing
     */
    handleSearch(e, containerId) {
        const val = e.target.value;
        const currentUrl = new URL(window.location.href);

        if (val && val.trim() !== '') {
            currentUrl.searchParams.set('search', val.trim());
        } else {
            currentUrl.searchParams.delete('search');
        }
        currentUrl.searchParams.set('page', '1');

        this.load(currentUrl.toString(), containerId, { preserveSearchFocus: true });
    },

    /**
     * Handle filter or search form submissions without page reload
     */
    handleFormSubmit(e, containerId, callback = null) {
        e.preventDefault();
        const form = e.target.closest('form');
        if (!form) return;

        const formData = new FormData(form);
        const currentUrl = new URL(window.location.href);

        for (const [key, value] of formData.entries()) {
            if (value !== '' && value !== null) {
                currentUrl.searchParams.set(key, value);
            } else {
                currentUrl.searchParams.delete(key);
            }
        }
        currentUrl.searchParams.set('page', '1');

        this.load(currentUrl.toString(), containerId, {
            onSuccess: () => {
                if (typeof callback === 'function') callback();
            }
        });
    }
};

// Global popstate handler for smooth back/forward browser navigation
window.addEventListener('popstate', (e) => {
    if (e.state && e.state.asyncContainerId && e.state.url) {
        window.SiBookAsync.load(e.state.url, e.state.asyncContainerId, { pushState: false });
    } else {
        const container = document.querySelector('[data-async-container]');
        if (container && container.id) {
            window.SiBookAsync.load(window.location.href, container.id, { pushState: false });
        }
    }
});
