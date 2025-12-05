<?php
	require_once "../../../db/config.php";

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	// Clear session data (including session token to prevent bfcache bypass)
	$_SESSION = [];
	session_unset();

	// Destroy session cookie
	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	}

	session_destroy();

	// Prevent caching of protected pages
	header('Cache-Control: no-cache, no-store, must-revalidate');
	header('Pragma: no-cache');
	header('Expires: 0');

	header("Location: ../../../index.php");
	exit;
?>