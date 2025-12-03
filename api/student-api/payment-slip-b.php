<?php
    require_once __DIR__ . "/../../db/config.php";

    // Check if student is logged in
    if (!isset($_SESSION['student'])) {
        // Redirect to the consolidated public login page
        header("Location: /Eduqueue-Queueing-System/index.php?public=1");
        exit;
    }

    $student = $_SESSION['student'];
    $error = "";
    $success = "";

    // If user comes to edit payment slip, preserve existing data
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_SESSION['payment_slip'])) {
        // Pre-populate form with existing session data
        $_POST['amount'] = $_SESSION['payment_slip']['amount'];
        $_POST['payment_for'] = $_SESSION['payment_slip']['payment_for'];
        $_POST['other_purpose'] = $_SESSION['payment_slip']['other_purpose'];
    }

    // Process payment slip form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_payment_slip'])) {
        // Validate required fields
        $amount = trim($_POST['amount'] ?? '');
        $payment_for = $_POST['payment_for'] ?? [];
        $other_purpose = trim($_POST['other_purpose'] ?? '');
        
        if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
            $error = "Please enter a valid amount.";
        } elseif (empty($payment_for)) {
            $error = "Please select at least one payment purpose.";
        } elseif (in_array('others', $payment_for) && empty($other_purpose)) {
            $error = "Please specify the purpose for 'Others'.";
        } else {
            // Store payment slip data in session
            $_SESSION['payment_slip'] = [
                'amount' => floatval($amount),
                'payment_for' => $payment_for,
                'other_purpose' => $other_purpose,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            // Set flag to indicate payment slip is complete
            $_SESSION['payment_slip_complete'] = true;
            
            // Clear any previous queue creation flag
            unset($_SESSION['queue_created_after_payment']);
            
            // Redirect to dashboard to get queue number
            header("Location: /eduqueue-queueing-system/student-management/student_dashboard.php");
            exit();
        }
    }
?>