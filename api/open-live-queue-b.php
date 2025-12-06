<?php
    // Include database config to fetch queue statistics for public display
    require_once "../db/config.php";
    
    // Fetch now serving (join with students to get name)
    $nowServing = $conn->query("
        SELECT q.queue_number, s.name, s.course, s.student_id
        FROM queue q 
        JOIN students s ON q.student_id = s.student_id
        WHERE q.status = 'serving' AND DATE(q.time_in) = CURDATE() 
        ORDER BY q.queue_id DESC LIMIT 1
    ")->fetch(PDO::FETCH_ASSOC);
    
    // Fetch next to serve (the oldest waiting queue)
    $nextServing = $conn->query("
        SELECT q.queue_number, s.name, s.course, s.student_id
        FROM queue q 
        JOIN students s ON q.student_id = s.student_id
        WHERE q.status = 'waiting' AND DATE(q.time_in) = CURDATE() 
        ORDER BY q.queue_id ASC LIMIT 1
    ")->fetch(PDO::FETCH_ASSOC);
    
    // Fetch waiting count
    $waitingCount = $conn->query("
        SELECT COUNT(*) FROM queue 
        WHERE status = 'waiting' AND DATE(time_in) = CURDATE()
    ")->fetchColumn();
    
    // Fetch served count
    $servedCount = $conn->query("
        SELECT COUNT(*) FROM queue 
        WHERE status = 'served' AND DATE(time_in) = CURDATE()
    ")->fetchColumn();
    
    // Fetch recent served (last 3)
    $recentServed = $conn->query("
        SELECT q.queue_number, s.student_id, q.time_out
        FROM queue q 
        JOIN students s ON q.student_id = s.student_id
        WHERE q.status = 'served' AND DATE(q.time_in) = CURDATE()
        ORDER BY q.time_out DESC LIMIT 3
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch all waiting students
    $waitingList = $conn->query("
        SELECT s.name, s.course, s.student_id
        FROM queue q 
        JOIN students s ON q.student_id = s.student_id
        WHERE q.status = 'waiting' AND DATE(q.time_in) = CURDATE()
        ORDER BY q.queue_id ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
?>