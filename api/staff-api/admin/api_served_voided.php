<?php
    include "../../../db/config.php";

    $data = [];

    $query = $conn->query("
        SELECT 
            DATE(time_in) AS date,
            SUM(CASE WHEN status = 'served' THEN 1 ELSE 0 END) AS served_count,
            SUM(CASE WHEN status = 'voided' THEN 1 ELSE 0 END) AS voided_count
        FROM queue
        GROUP BY DATE(time_in)
        ORDER BY DATE(time_in)
    ");

    if ($query) {
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $data = [];
    }

    header("Content-Type: application/json");
    echo json_encode($data);
?>