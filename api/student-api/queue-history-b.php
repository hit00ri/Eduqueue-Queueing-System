<?php
    // queue-history-b.php
    require_once __DIR__ . "/../../db/config.php";

    if (!isset($_SESSION['student'])) {
        header("Location: /eduqueue-queueing-system/student-management/student_login.php");
        exit;
    }

    $student = $_SESSION['student'];
    $queueHistory = [];

    // Get queue history for the logged-in student only
    $historyQuery = $conn->prepare("
        SELECT 
            queue_number, 
            status, 
            time_in, 
            time_out, 
            amount, 
            payment_for 
        FROM queue 
        WHERE student_id = ? 
        ORDER BY time_in DESC 
        LIMIT 50
    ");
    $historyQuery->execute([$student['student_id']]);
    $queueHistory = $historyQuery->fetchAll(PDO::FETCH_ASSOC);

    // Calculate statistics for the logged-in student
    $totalQueues = count($queueHistory);
    $completedQueues = count(array_filter($queueHistory, fn($q) => $q['status'] === 'served'));
    $waitingQueues = count(array_filter($queueHistory, fn($q) => $q['status'] === 'waiting'));
    $servingQueues = count(array_filter($queueHistory, fn($q) => $q['status'] === 'serving'));
?>