<?php
    // Student protection include: start session, send no-cache headers, ensure student is logged in
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_path', '/');
        session_start();
    }

    // Strong no-cache headers so browser won't serve cached authenticated pages
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Expires: 0');

    if (!isset($_SESSION['student'])) {
        // Redirect to public login (use existing public entry)
        header('Location: /Eduqueue-Queueing-System/index.php?public=1');
        exit;
    }

?>
