<?php
require_once __DIR__ . '/../db/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if action parameter exists
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header('Location: ../index.php?error=empty_fields');
        exit();
    }

    // `db/config.php` exposes a PDO connection in `$conn`.
    if (!isset($conn) || !$conn instanceof PDO) {
        // Helpful error for runtime if config didn't provide a PDO instance
        trigger_error('Database connection ($conn) not found. Check db/config.php', E_USER_ERROR);
    }
    $db = $conn;

    // Check in users table (staff/admin)
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Use password_verify() for hashed passwords, fallback to plain compare
        if ((isset($user['password']) && password_verify($password, $user['password'])) || $password === $user['password']) {
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'id' => $user['user_id'],
                'name' => $user['name'],
                'username' => $user['username'] ?? $user['email'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            // Generate session token used by validate_session_token()
            if (function_exists('generate_session_token')) {
                generate_session_token();
            }

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: /Eduqueue-Queueing-System/staff-management/admin/admin_dashboard.php');
            } else {
                header('Location: /Eduqueue-Queueing-System/staff-management/cashier/dashboard.php');
            }
            exit();
        }
    }

    // Also check in students table
    $query = "SELECT * FROM students WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check student password (accept hashed or plain)
        if ((isset($student['password']) && password_verify($password, $student['password'])) || $password === $student['password']) {
            $_SESSION['student'] = [
                'id' => $student['student_id'],
                'student_id' => $student['student_id'],
                'name' => $student['name'],
                'email' => $student['email']
            ];
            // Generate session token used by validate_session_token()
            if (function_exists('generate_session_token')) {
                generate_session_token();
            }

            header('Location: /Eduqueue-Queueing-System/student-management/student_dashboard.php');
            exit();
        }
    }

    // Login failed
    header('Location: ../index.php?error=invalid_credentials');
    exit();

} elseif ($action === 'logout') {
    // Clear all session data
    $_SESSION = [];

    // Delete session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
    header('Location: ../index.php');
    exit();
}
?>