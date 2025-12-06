<?php
    require_once __DIR__ . "/../../../db/config.php";

    if (isset($_SESSION['user'])) {
        header('Location: /eduqueue-queueing-system/staff-management/admin/admin_dashboard.php');
        exit;
    }

    $err = '';

    // Surface any flash set by prior POST
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        if (!empty($flash['text'])) {
            $err = $flash['text'];
        }
        unset($_SESSION['flash']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $identifier = trim($_POST['username'] ?? $_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Allow login by username OR email for staff accounts
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['password']) {
            // Check if user is admin
            if ($user['role'] === 'admin') {
                $_SESSION['user'] = $user;
                header('Location: /Eduqueue-Queueing-System/staff-management/admin/admin_dashboard.php');
                exit;
            } else {
                // Not an admin
                $_SESSION['flash'] = ['type' => 'error', 'text' => 'Access denied. Admin privileges required.'];
                header('Location: /Eduqueue-Queueing-System/staff-management/admin/admin_login.php');
                exit;
            }
        } else {
            // Invalid credentials -> set flash and redirect (PRG)
            $_SESSION['flash'] = ['type' => 'error', 'text' => 'Invalid credentials'];
            header('Location: /Eduqueue-Queueing-System/staff-management/admin/admin_login.php');
            exit;
        }
    }
?>