<?php
/**
 * Universal protection include for all authenticated pages.
 * Usage: put `require_once __DIR__ . '/api/protect.php';` (adjust path) at the top of protected pages.
 */

if (session_status() === PHP_SESSION_NONE) {
    // Make sure session cookie is available across the project
    ini_set('session.cookie_path', '/');
    session_start();
}

// Strong no-cache headers so browser Back/Forward won't show cached authenticated pages
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');
header('Surrogate-Control: no-store');

// Load helper functions like validate_session_token() and the PDO $conn
require_once __DIR__ . '/../db/config.php';

// If no valid session, destroy session and redirect to login
if (!isset($_SESSION['user']) && !isset($_SESSION['student'])) {
    // clear any lingering session state
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
    // Redirect to public login page
    header('Location: /Eduqueue-Queueing-System/index.php?error=login_required');
    exit();
}

// Validate the per-session token (function defined in db/config.php)
// If token is missing/expired, validate_session_token() will redirect and exit.
validate_session_token('/Eduqueue-Queueing-System/index.php?error=login_required');

// JavaScript to force reload when page is restored from BFCache (back/forward cache).
// This avoids showing stale authenticated pages when user navigates with Forward/Back.
// Include this small script near the top of the page; it's safe to output here.
echo '<script>window.addEventListener("pageshow", function(e){ if (e.persisted) { window.location.reload(); }});</script>';

// Debug endpoint: show session/token info when ?debug_protect=1 is present.
// Only allowed when APP_DEBUG=1 or request originates from localhost.
if (isset($_GET['debug_protect'])) {
    $remote = $_SERVER['REMOTE_ADDR'] ?? null;
    $isLocal = in_array($remote, ['127.0.0.1', '::1', 'localhost'], true);
    $appDebug = getenv('APP_DEBUG') === '1';
    if ($appDebug || $isLocal) {
        header('Content-Type: application/json');
        $data = [
            'session_id' => session_id(),
            'session_name' => session_name(),
            'remote_addr' => $remote,
            'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
            'session_keys' => array_values(array_keys($_SESSION)),
            'session' => $_SESSION,
            'token_present' => isset($_SESSION['_token']),
            'token_time' => $_SESSION['_token_time'] ?? null,
            'token_age_seconds' => isset($_SESSION['_token_time']) ? (time() - $_SESSION['_token_time']) : null,
            'token_valid' => (isset($_SESSION['_token_time']) && ((time() - $_SESSION['_token_time']) <= 3600)),
            'cookie_params' => session_get_cookie_params(),
        ];
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit();
    }
    http_response_code(403);
    echo 'Debug not allowed';
    exit();
}

?>