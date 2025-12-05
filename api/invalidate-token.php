<?php
/**
 * Token invalidation endpoint
 * Called via sendBeacon when user navigates away from protected pages
 * This invalidates the session token to prevent bfcache bypass
 */
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_path', '/');
    session_start();
}

require_once __DIR__ . '/../db/config.php';

// Invalidate the token when user leaves the page
if (isset($_SESSION['_token'])) {
    invalidate_session_token();
}

// Response not critical since this is sent via sendBeacon
http_response_code(204); // No Content
?>
