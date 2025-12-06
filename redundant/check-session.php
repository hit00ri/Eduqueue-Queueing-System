<?php
/**
 * Session validation endpoint
 * Returns JSON status of current session
 * If session is invalid, returns 401 Unauthorized
 */
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_path', '/');
    session_start();
}

require_once __DIR__ . '/../db/config.php';

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

// Check if user is logged in AND has valid token
$is_authenticated = false;

if (isset($_SESSION['user'])) {
    // Staff user
    validate_session_token('/'); // Will exit with redirect if invalid
    $is_authenticated = true;
} elseif (isset($_SESSION['student'])) {
    // Student user
    validate_session_token('/');
    $is_authenticated = true;
}

if ($is_authenticated) {
    http_response_code(200);
    echo json_encode(['status' => 'valid']);
} else {
    http_response_code(401);
    echo json_encode(['status' => 'invalid']);
}
exit;
?>
