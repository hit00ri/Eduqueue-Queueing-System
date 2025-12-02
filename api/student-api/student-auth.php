<?php
/**
 * Student authentication handler (renamed from student-login-b.php to avoid confusion).
 * Usage: include this file where student login POSTs should be processed.
 */
require_once __DIR__ . '/../../db/config.php';

$error = '';

// If already logged in as student redirect to dashboard
if (isset($_SESSION['student'])) {
    header('Location: /Eduqueue-Queueing-System/student-management/student_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($student_id === '' || $password === '') {
        $error = 'Please fill in both fields.';
    } else {
        try {
            $stmt = $conn->prepare('SELECT * FROM students WHERE student_id = ? LIMIT 1');
            $stmt->execute([$student_id]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($student && $password === $student['password']) {
                // Successful login
                $_SESSION['student'] = $student;
                header('Location: /Eduqueue-Queueing-System/student-management/student_dashboard.php');
                exit;
            } else {
                $error = 'Invalid student ID or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . htmlspecialchars($e->getMessage());
        }
    }
}

?>
