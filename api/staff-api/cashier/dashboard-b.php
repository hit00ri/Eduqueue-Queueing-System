<?php
require_once "../../db/config.php";

// Check if user is logged in and is cashier
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'cashier') { 
//     header("Location: /Eduqueue-Queueing-System/staff-management/cashier/index.php");
//     exit; 
// }

// ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle regular actions
    if (isset($_POST['call_next'])) {
        $next = $conn->query("SELECT queue_id FROM queue WHERE status='waiting' ORDER BY queue_id ASC LIMIT 1")
            ->fetchColumn();

        if ($next) {
            $stmt = $conn->prepare("UPDATE queue SET status='serving' WHERE queue_id=?");
            $stmt->execute([$next]);
            $_SESSION['payment_message'] = "Now serving next customer";
            $_SESSION['payment_type'] = 'info';
        }
    }

    if (isset($_POST['served'])) {
        $id = intval($_POST['queue_id']);
        $stmt = $conn->prepare("UPDATE queue SET status='served', time_out = NOW() WHERE queue_id=?");
        $stmt->execute([$id]);
        $_SESSION['payment_message'] = "Queue marked as served";
        $_SESSION['payment_type'] = 'info';
    }

    if (isset($_POST['voided'])) {
        $id = intval($_POST['queue_id']);
        $stmt = $conn->prepare("UPDATE queue SET status='voided' WHERE queue_id=?");
        $stmt->execute([$id]);
        $_SESSION['payment_message'] = "Queue voided";
        $_SESSION['payment_type'] = 'warning';
    }

    // Handle payment completion
    if (isset($_POST['action']) && $_POST['action'] === 'complete_payment') {
        $queueId = $_POST['queue_id'] ?? null;
        $amount = $_POST['amount'] ?? null;
        $paymentType = $_POST['payment_type'] ?? null;
        $paymentFor = $_POST['payment_for'] ?? 'Tuition Fee';

        if ($queueId && $amount && $paymentType) {
            try {
                $cashierId = $_SESSION['user']['user_id'] ?? 2; // Default to cashier ID 2 if not set

                // Get student ID and queue number
                $queueQuery = $conn->prepare("SELECT student_id, queue_number FROM queue WHERE queue_id = ?");
                $queueQuery->execute([$queueId]);
                $queueData = $queueQuery->fetch(PDO::FETCH_ASSOC);

                if ($queueData) {
                    $studentId = $queueData['student_id'];
                    $queueNumber = $queueData['queue_number'];

                    // Insert transaction
                    $transactionQuery = $conn->prepare("
                            INSERT INTO transactions (queue_id, amount, payment_type, cashier_id, date_paid) 
                            VALUES (?, ?, ?, ?, NOW())
                        ");
                    $transactionQuery->execute([$queueId, $amount, $paymentType, $cashierId]);
                    $transactionId = $conn->lastInsertId();

                    // Update queue status
                    $updateQuery = $conn->prepare("
                            UPDATE queue 
                            SET status = 'served', 
                                time_out = NOW(),
                                payment_for = ?,
                                amount = ?,
                                handled_by = ?
                            WHERE queue_id = ?
                        ");
                    $updateQuery->execute([$paymentFor, $amount, $cashierId, $queueId]);

                    // Add to payment history
                    $historyQuery = $conn->prepare("
                            INSERT INTO payment_history (student_id, transaction_id, status, date) 
                            VALUES (?, ?, 'completed', NOW())
                        ");
                    $historyQuery->execute([$studentId, $transactionId]);

                    $_SESSION['payment_message'] = "Payment of ₱" . number_format($amount, 2) . " completed for Queue #{$queueNumber}";
                    $_SESSION['payment_type'] = 'success';
                } else {
                    $_SESSION['payment_message'] = "Queue not found";
                    $_SESSION['payment_type'] = 'danger';
                }
            } catch (Exception $e) {
                $_SESSION['payment_message'] = "Payment failed: " . $e->getMessage();
                $_SESSION['payment_type'] = 'danger';
            }
        } else {
            $_SESSION['payment_message'] = "Please fill all payment details";
            $_SESSION['payment_type'] = 'danger';
        }
    }

    header('Location: /eduqueue-queueing-system/staff-management/cashier/dashboard.php');
    exit;
}

// QUERY DATA
$serving = $conn->query("SELECT q.*, s.name FROM queue q JOIN students s ON q.student_id = s.student_id WHERE q.status = 'serving' ORDER BY q.queue_id ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

$waiting = $conn->query("SELECT q.*, s.name FROM queue q JOIN students s ON q.student_id = s.student_id WHERE q.status = 'waiting' ORDER BY q.queue_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>