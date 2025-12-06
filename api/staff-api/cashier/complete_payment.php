<?php
    session_start();
    require_once __DIR__ . '/../../../db/config.php';

    header('Content-Type: application/json');

    // Check if user is logged in
    if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'cashier' && $_SESSION['user']['role'] !== 'admin')) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        exit();
    }

    // Check if it's a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit();
    }

    // Get POST data
    $queueId = $_POST['queue_id'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $paymentType = $_POST['payment_type'] ?? null;
    $paymentFor = $_POST['payment_for'] ?? 'Tuition Fee';
    $cashierId = $_SESSION['user']['user_id'];

    // Validate input
    if (!$queueId || !$amount || !$paymentType) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }

    try {
        $conn->beginTransaction();

        // Get student ID and queue number from queue
        $queueQuery = "SELECT q.student_id, q.queue_number FROM queue q WHERE q.queue_id = :queue_id";
        $queueStmt = $conn->prepare($queueQuery);
        $queueStmt->bindParam(':queue_id', $queueId);
        $queueStmt->execute();
        $queueData = $queueStmt->fetch(PDO::FETCH_ASSOC);

        if (!$queueData) {
            throw new Exception("Queue not found");
        }

        $studentId = $queueData['student_id'];
        $queueNumber = $queueData['queue_number'];

        // Insert transaction
        $transactionQuery = "
            INSERT INTO transactions (queue_id, amount, payment_type, cashier_id, date_paid) 
            VALUES (:queue_id, :amount, :payment_type, :cashier_id, NOW())
        ";
        $transactionStmt = $conn->prepare($transactionQuery);
        $transactionStmt->bindParam(':queue_id', $queueId);
        $transactionStmt->bindParam(':amount', $amount);
        $transactionStmt->bindParam(':payment_type', $paymentType);
        $transactionStmt->bindParam(':cashier_id', $cashierId);
        $transactionStmt->execute();
        $transactionId = $conn->lastInsertId();

        // Update queue status
        $updateQueueQuery = "
            UPDATE queue 
            SET status = 'served', 
                time_out = NOW(),
                payment_for = :payment_for,
                handled_by = :handled_by
            WHERE queue_id = :queue_id
        ";
        $updateQueueStmt = $conn->prepare($updateQueueQuery);
        $updateQueueStmt->bindParam(':queue_id', $queueId);
        $updateQueueStmt->bindParam(':payment_for', $paymentFor);
        $updateQueueStmt->bindParam(':handled_by', $cashierId);
        $updateQueueStmt->execute();

        // Add to payment history
        $historyQuery = "
            INSERT INTO payment_history (student_id, transaction_id, status, date) 
            VALUES (:student_id, :transaction_id, 'completed', NOW())
        ";
        $historyStmt = $conn->prepare($historyQuery);
        $historyStmt->bindParam(':student_id', $studentId);
        $historyStmt->bindParam(':transaction_id', $transactionId);
        $historyStmt->execute();

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => "Payment of ₱" . number_format($amount, 2) . " completed for Queue #{$queueNumber}",
            'transaction_id' => $transactionId,
            'queue_number' => $queueNumber,
            'amount' => $amount,
            'payment_type' => $paymentType
        ]);

    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Payment Error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Payment failed: ' . $e->getMessage()
        ]);
    }
?>