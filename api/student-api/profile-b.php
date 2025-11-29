<?php
// profile-b.php
require_once __DIR__ . "/../../db/config.php";

// Check if student is logged in
if (!isset($_SESSION['student'])) {
    header("Location: /eduqueue-queueing-system/student-management/student_login.php");
    exit;
}

$student = $_SESSION['student'];
$error = '';
$success = '';

// Get updated student data
$studentQuery = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$studentQuery->execute([$student['student_id']]);
$studentData = $studentQuery->fetch(PDO::FETCH_ASSOC);

// Process profile update form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $year_level = trim($_POST['year_level'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Basic validation
    if (empty($name) || empty($course) || empty($year_level) || empty($email)) {
        $error = "All fields except password are required.";
    } else {
        try {
            // Check if email is already taken by another student
            $emailCheck = $conn->prepare("SELECT student_id FROM students WHERE email = ? AND student_id != ?");
            $emailCheck->execute([$email, $student['student_id']]);
            
            if ($emailCheck->fetch()) {
                $error = "Email is already registered by another student.";
            } else {
                // Update basic profile information
                $updateStmt = $conn->prepare("UPDATE students SET name = ?, course = ?, year_level = ?, email = ? WHERE student_id = ?");
                $updateStmt->execute([$name, $course, $year_level, $email, $student['student_id']]);
                
                // Update password if provided
                if (!empty($new_password)) {
                    if (empty($current_password)) {
                        $error = "Current password is required to change password.";
                    } elseif ($studentData['password'] !== $current_password) {
                        $error = "Current password is incorrect.";
                    } elseif ($new_password !== $confirm_password) {
                        $error = "New passwords do not match.";
                    } elseif (strlen($new_password) < 3) {
                        $error = "New password must be at least 3 characters long.";
                    } else {
                        $passwordStmt = $conn->prepare("UPDATE students SET password = ? WHERE student_id = ?");
                        $passwordStmt->execute([$new_password, $student['student_id']]);
                        $success = "Profile and password updated successfully!";
                    }
                } else {
                    $success = "Profile updated successfully!";
                }
                
                // Refresh student data
                $studentQuery->execute([$student['student_id']]);
                $studentData = $studentQuery->fetch(PDO::FETCH_ASSOC);
                $_SESSION['student'] = array_merge($_SESSION['student'], $studentData);
            }
        } catch (PDOException $e) {
            $error = "Update failed: " . $e->getMessage();
        }
    }
}
?>