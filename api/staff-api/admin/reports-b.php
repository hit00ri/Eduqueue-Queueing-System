<?php
    require_once __DIR__ . "/../../../db/config.php";

    // Include central auth (ensures login and no-cache headers)
    require_once __DIR__ . "/../../auth.php";

    // Check if MetricsService exists before including
    $metricsServicePath = __DIR__ . "/../../../services/MetricsService.php";
    if (file_exists($metricsServicePath)) {
        require_once $metricsServicePath;
    }

    // Initialize metrics service if available
    $metricsService = null;
    if (class_exists('MetricsService')) {
        $metricsService = new MetricsService($conn);
    }

    // Get queue data
    $data = $conn->query("
        SELECT q.*, s.name 
        FROM queue q
        JOIN students s ON q.student_id = s.student_id
        ORDER BY q.queue_id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Get transaction data
    $transactions = $conn->query("
        SELECT * FROM transactions 
        ORDER BY date_paid DESC 
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Counts - FIXED VERSION (using your existing queue table)
    $servedCount = $conn->query("
        SELECT COUNT(*) as count FROM queue 
        WHERE status = 'served' AND DATE(time_in) = CURDATE()
    ")->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    $waitingCount = $conn->query("
        SELECT COUNT(*) as count FROM queue 
        WHERE status = 'waiting' AND DATE(time_in) = CURDATE()
    ")->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // Try to get today's summary if metrics tables exist
    $todaySummary = null;
    try {
        $todaySummary = $conn->query("
            SELECT * FROM daily_kpi_summary 
            WHERE summary_date = CURDATE()
        ")->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $todaySummary = null;
    }

    // If metrics service is available, generate summary
    if ($metricsService && !$todaySummary) {
        try {
            $metricsService->generateDailyKPISummary(date('Y-m-d'));
            $todaySummary = $conn->query("SELECT * FROM daily_kpi_summary WHERE summary_date = CURDATE()")->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            
        }
    }

    // If there is still no summary (no metrics table or service), compute a basic summary from queue/transactions
    if (!$todaySummary) {
        $total_queues = (int) ($conn->query("SELECT COUNT(*) FROM queue WHERE DATE(time_in) = CURDATE()")->fetchColumn() ?: 0);
        $served = (int) ($conn->query("SELECT COUNT(*) FROM queue WHERE status = 'served' AND DATE(time_in) = CURDATE()")->fetchColumn() ?: 0);
        $voided = (int) ($conn->query("SELECT COUNT(*) FROM queue WHERE status = 'voided' AND DATE(time_in) = CURDATE()")->fetchColumn() ?: 0);
        $avg_wait = $conn->query("SELECT AVG(TIMESTAMPDIFF(SECOND, time_in, time_out)) FROM queue WHERE status = 'served' AND DATE(time_in) = CURDATE()")->fetchColumn();
        $avg_service = $avg_wait !== null ? (float) $avg_wait : null;
        $total_trans = (float) ($conn->query("SELECT SUM(amount) FROM transactions WHERE DATE(date_paid) = CURDATE()")->fetchColumn() ?: 0);
        $eff = $total_queues > 0 ? round(($served / $total_queues) * 100, 1) : 0;

        $todaySummary = [
            'service_efficiency_rate' => $eff,
            'avg_wait_time' => $avg_wait !== null ? (float) $avg_wait : null,
            'avg_service_time' => $avg_service !== null ? (float) $avg_service : null,
            'total_transaction_volume' => $total_trans,
            'voided_count' => $voided,
            'total_queues' => $total_queues,
        ];
    }
?>