/**
 * Session Guard: Prevents bfcache bypass by:
 * 1. Invalidating token when user navigates away (pagehide)
 * 2. Checking token validity on pageshow restore
 * 3. Forcing reload on back/forward to protected pages
 * 
 * Usage: Add to any protected page (after login required)
 *   <script src="../js/session-guard.js"></script>
 */

(function () {
    'use strict';

    // Track whether the user clicked an internal navigation link or submitted an internal form.
    // If so, we should NOT invalidate the token on pagehide because the next page is still
    // part of the protected app and will validate the session.
    let skipInvalidate = false;

    function isInternalHref(href) {
        try {
            const url = new URL(href, window.location.origin);
            return url.origin === window.location.origin && url.pathname.startsWith(window.location.pathname.split('/').slice(0,2).join('/'));
        } catch (e) {
            return false;
        }
    }

    // Mark internal navigation when user clicks an anchor that stays within the app
    document.addEventListener('click', function (e) {
        const a = e.target.closest && e.target.closest('a');
        if (!a || !a.getAttribute) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#')) return;
        // Consider relative and absolute same-origin links as internal
        try {
            const targetUrl = new URL(href, window.location.href);
            if (targetUrl.origin === window.location.origin) {
                skipInvalidate = true;
            }
        } catch (err) {
            // If URL parsing fails, assume relative => internal
            skipInvalidate = true;
        }
    }, true);

    // Also treat form submissions as internal navigations
    document.addEventListener('submit', function () {
        skipInvalidate = true;
    }, true);

    // When user navigates away (including back button), invalidate token on server
    window.addEventListener('pagehide', function (event) {
        // If we detected an internal navigation, skip invalidation so the next protected page
        // can validate the same session token.
        if (skipInvalidate) return;

        // Send beacon to invalidate token (non-blocking)
        if (navigator.sendBeacon) {
            navigator.sendBeacon('/Eduqueue-Queueing-System/api/invalidate-token.php');
        }
    });

    // When page is restored from bfcache or navigated via back/forward
    window.addEventListener('pageshow', function (event) {
        // If page was restored from bfcache (persisted = true)
        if (event.persisted) {
            // Force reload to re-validate session server-side
            // This will trigger the protection checks in the PHP files
            window.location.reload();
        }
    });
})();
