<?php
require_once __DIR__ . "/../../db/config.php";

if (isset($_SESSION['user']) || isset($_SESSION['student'])) { 
    header('Location: /Eduqueue-Queueing-System/index.php'); 
    exit; 
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Try staff login first (admin or cashier)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
        // Staff login successful
        $_SESSION['user'] = $user;
        
        // Redirect based on user role
        if ($user['role'] === 'admin') {
            header('Location: /Eduqueue-Queueing-System/staff-management/admin/admin_dashboard.php');
        } else {
            header('Location: /Eduqueue-Queueing-System/staff-management/cashier/dashboard.php');
        }
        exit;
    }

    // Try student login
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ? LIMIT 1");
    $stmt->execute([$username]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student && $password === $student['password']) {
        // Student login successful
        $_SESSION['student'] = $student;
        header("Location: /Eduqueue-Queueing-System/student-management/student_dashboard.php");
        exit;
    }

    // Neither staff nor student found
    $err = 'Invalid username/student ID or password';
}
?>