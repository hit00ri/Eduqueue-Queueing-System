<?php
require_once __DIR__ . "/../../../db/config.php";

// Check if user is already logged in
if (isset($_SESSION['user'])) {
    header('Location: /eduqueue-queueing-system/staff-management/admin/admin_dashboard.php'); 
    exit; 
}

// Preparing an error message
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $password === $user['password']) {
        // Check if user is admin
        if ($user['role'] === 'admin') {
            $_SESSION['user'] = $user;
            header('Location: /eduqueue-queueing-system/staff-management/admin/admin_dashboard.php');
            exit;
        } else {
            $err = 'Access denied. Admin privileges required.';
        }
    } else {
        $err = 'Invalid credentials';
    }
}
?>