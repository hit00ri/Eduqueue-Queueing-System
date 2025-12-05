<?php
// Ensure sessions work across all paths in the project
if (session_status() === PHP_SESSION_NONE) {
    // Set cookie path to project root so session persists across subfolders
    ini_set('session.cookie_path', '/');
    session_start();
}

$host = "localhost";
$username = "root";
$database = "queue_db";
$password = "";

try{
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("Connection failed: " . $e->getMessage());
}

/**
 * Generate a secure session token and store it in $_SESSION.
 * Used to prevent bfcache bypass by invalidating tokens on navigation.
 */
function generate_session_token() {
    $_SESSION['_token'] = bin2hex(random_bytes(32));
    $_SESSION['_token_time'] = time();
    return $_SESSION['_token'];
}

/**
 * Validate the session token. If invalid/missing, destroy session and redirect to login.
 * Also checks if token has expired (more than 1 hour old).
 * @param string $redirectUrl URL to redirect to if token is invalid
 */
function validate_session_token($redirectUrl = '/Eduqueue-Queueing-System/index.php') {
    // Check if token exists
    if (!isset($_SESSION['_token']) || empty($_SESSION['_token'])) {
        // Token missing - session is invalid
        session_destroy();
        header('Location: ' . $redirectUrl);
        exit;
    }
    
    // Check if token has expired (1 hour expiry)
    $tokenTime = $_SESSION['_token_time'] ?? 0;
    $currentTime = time();
    $maxAge = 3600; // 1 hour
    
    if (($currentTime - $tokenTime) > $maxAge) {
        // Token expired
        session_destroy();
        header('Location: ' . $redirectUrl);
        exit;
    }
}

/**
 * Invalidate the current session token to prevent bfcache restore.
 * This is called when navigating away from protected pages.
 */
function invalidate_session_token() {
    if (isset($_SESSION['_token'])) {
        unset($_SESSION['_token']);
        unset($_SESSION['_token_time']);
    }
}

?>
