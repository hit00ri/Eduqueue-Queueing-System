<?php
    // Central authentication include.
    // Usage: require_once __DIR__ . '/includes/auth.php';
    //        require_role('admin'); // optional role check

    if (session_status() === PHP_SESSION_NONE) {
        // Ensure session cookie is available across project
        ini_set('session.cookie_path', '/');
        session_start();
    }

    // Strong no-cache headers for authenticated pages so browsers revalidate
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Expires: 0');

    function require_role(string $role) {
        // Ensure session is active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /Eduqueue-Queueing-System/index.php');
            exit;
        }

        if (strtolower($_SESSION['user']['role'] ?? '') !== strtolower($role)) {
            // Not authorized for this role
            header('Location: /Eduqueue-Queueing-System/index.php');
            exit;
        }
    }

    function ensure_logged_in() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /Eduqueue-Queueing-System/index.php');
            exit;
        }
    }

    // By default, simply ensure the user is logged in when this file is included.
    // Callers that need a specific role should call require_role() after including.
    ensure_logged_in();

?>
