<?php
require_once __DIR__ . "/../../db/config.php";

// Check if user is logged in and is admin
 require_once __DIR__ . "/../../auth.php";
 require_role('admin');

// Fetch active queues
$queues = $conn->query("
    SELECT q.queue_number, q.status, q.time_in, s.name AS student_name
    FROM queue q
    JOIN students s ON q.student_id = s.student_id
    WHERE DATE(q.time_in) = CURDATE()
    ORDER BY q.queue_number DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>