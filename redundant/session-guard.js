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

    // When user navigates away (including back button), invalidate token on server
    window.addEventListener('pagehide', function (event) {
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
