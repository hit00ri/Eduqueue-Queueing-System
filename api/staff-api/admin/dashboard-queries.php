<?php
// include DB config using an absolute path based on this file's directory
$dbConfig = __DIR__ . '/../../../db/config.php';
if (file_exists($dbConfig)) {
    require_once $dbConfig; // provides $conn (PDO)
} else {
    // fail early with a helpful message
    error_log('Missing DB config: ' . $dbConfig);
    echo '<div class="alert alert-danger">Configuration file not found: ' . htmlspecialchars($dbConfig) . '</div>';
    // stop further execution to avoid fatal errors
    return;
}

try {

    // 1. Total Users
    $total_users = (int) $conn->query("SELECT COUNT(*) AS c FROM users")->fetchColumn();

    // 2. Total Students
    $total_students = (int) $conn->query("SELECT COUNT(*) AS c FROM students")->fetchColumn();

    // 3. Total Queue Entries Today
    $total_queue_today = (int) $conn->query("
        SELECT COUNT(*) AS c 
        FROM queue 
        WHERE DATE(time_in) = CURDATE()
    ")->fetchColumn();

    // 4. Students Waiting Now
    $waiting = (int) $conn->query("
        SELECT COUNT(*) AS c 
        FROM queue 
        WHERE status = 'waiting'
    ")->fetchColumn();

    // 5. Served Today
    $served_today = (int) $conn->query("
        SELECT COUNT(*) AS c 
        FROM queue 
        WHERE status = 'served'
        AND DATE(time_in) = CURDATE()
    ")->fetchColumn();

    // 6. Voided Today
    $voided_today = (int) $conn->query("
        SELECT COUNT(*) AS c 
        FROM queue 
        WHERE status = 'voided'
        AND DATE(time_in) = CURDATE()
    ")->fetchColumn();

    // 7. Revenue Today
    $revenue_today = (float) $conn->query("
        SELECT COALESCE(SUM(amount), 0) AS total 
        FROM transactions
        WHERE DATE(date_paid) = CURDATE()
    ")->fetchColumn();

    // 8. Transactions Today
    $transactions_today = (int) $conn->query("
        SELECT COUNT(*) AS c 
        FROM transactions
        WHERE DATE(date_paid) = CURDATE()
    ")->fetchColumn();

    // 9. Average Wait Time Today
    $avg_wait_today = (float) $conn->query("
        SELECT COALESCE(AVG(wait_time_seconds), 0) AS avg_wait
        FROM system_metrics
        WHERE DATE(date_created) = CURDATE()
    ")->fetchColumn();

    // 10. Average Service Time Today
    $avg_service_today = (float) $conn->query("
        SELECT COALESCE(AVG(service_time_seconds), 0) AS avg_service
        FROM system_metrics
        WHERE DATE(date_created) = CURDATE()
    ")->fetchColumn();

} catch (Exception $e) {

    error_log("Dashboard Query Error: " . $e->getMessage());

    // default fallback values
    $total_users = $total_students = $total_queue_today = 0;
    $waiting = $served_today = $voided_today = $transactions_today = 0;
    $revenue_today = $avg_wait_today = $avg_service_today = 0.0;
}
?>