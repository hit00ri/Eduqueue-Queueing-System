<?php
require_once __DIR__ . "/../../db/config.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($name) || empty($course) || empty($year_level) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 3) {
        $error = "Password must be at least 3 characters long.";
    } else {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT student_id FROM students WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email already registered.";
            } else {
                // Insert new student
                $stmt = $conn->prepare("INSERT INTO students (name, course, year_level, email, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $course, $year_level, $email, $password]);

                $success = "Registration successful! You can now login.";
            }
        } catch (PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>