<?php
require_once __DIR__ . "/../../db/config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { 
    header("Location: /eduqueue-queueing-system/staff-management/admin/admin_login.php");
    exit; 
}

// Fetch active queues
$queues = $conn->query("
    SELECT q.queue_number, q.status, q.time_in, s.name AS student_name
    FROM queue q
    JOIN students s ON q.student_id = s.student_id
    WHERE DATE(q.time_in) = CURDATE()
    ORDER BY q.queue_number DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>