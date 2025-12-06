<?php
require_once "../db/config.php";

$message = "";
$error = "";
$email_value = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $new_password = trim($_POST["new_password"] ?? "");
    $confirm_password = trim($_POST["confirm_password"] ?? "");
    $email_value = htmlspecialchars($email);

    if ($email === "" || $new_password === "" || $confirm_password === "") {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 3) {
        $error = "Password must be at least 3 characters long.";
    } else {
        try {
            // Check if email exists in users table (admin/cashier)
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user_exists = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $table_to_update = "";
            
            if ($user_exists) {
                $table_to_update = "users";
            } else {
                // Check if email exists in students table
                $stmt = $conn->prepare("SELECT student_id FROM students WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                $student_exists = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($student_exists) {
                    $table_to_update = "students";
                } else {
                    $error = "Email not found in our system.";
                }
            }
            
            // If email was found, update the password (in plain text as per your system)
            if ($table_to_update && empty($error)) {
                // Store as plain text (matching your profile-b.php system)
                if ($table_to_update === "users") {
                    $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                } else {
                    $update = $conn->prepare("UPDATE students SET password = ? WHERE email = ?");
                }
                
                $result = $update->execute([$new_password, $email]);
                
                if ($result && $update->rowCount() > 0) {
                    $message = "Password updated successfully. You can now sign in.";
                    $email_value = ""; // Clear the email field
                } else {
                    $error = "Failed to update password. Please try again.";
                }
            }
            
        } catch (PDOException $e) {
            error_log("Password reset error: " . $e->getMessage());
            $error = "An error occurred while processing your request. Please try again later.";
        }
    }
}
?>
