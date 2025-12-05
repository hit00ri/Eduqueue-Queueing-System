<?php
require_once __DIR__ . '/../db/config.php';

// Check if action parameter exists
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($emaigitl) || empty($password)) {
        header('Location: ../index.php?error=empty_fields');
        exit();
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Check in users table (staff/admin)
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // IMPORTANT: Use password_verify() if you hash passwords!
        // For now, simple check (you should hash passwords in production)
        if ($password === $user['password']) {
            $_SESSION['user'] = [
                'id' => $user['user_id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: ../admin-dashboard.php');
            } else {
                header('Location: ../cashier-dashboard.php');
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
        
        // Check student password (adjust field name if needed)
        if ($password === $student['password']) {
            $_SESSION['student'] = [
                'id' => $student['student_id'],
                'name' => $student['name'],
                'email' => $student['email'],
                'student_id' => $student['student_id']
            ];
            
            header('Location: ../student-dashboard.php');
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
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    header('Location: ../index.php');
    exit();
}
?>