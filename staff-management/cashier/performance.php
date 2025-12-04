<?php
require_once '../../db/config.php';

// Fetch data from the `queue` table for performance metrics
$stmtQueueMetrics = $conn->prepare(
    "SELECT COUNT(*) AS total_served, 
            AVG(TIMESTAMPDIFF(MINUTE, time_in, time_out)) AS avg_service_time 
     FROM queue 
     WHERE status = 'served'"
);
$stmtQueueMetrics->execute();
$queueMetrics = $stmtQueueMetrics->fetch(PDO::FETCH_ASSOC);

// Fetch data from the `transactions` table for financial metrics
$stmtTransactionMetrics = $conn->prepare(
    "SELECT SUM(amount) AS total_revenue, 
            COUNT(*) AS total_transactions 
     FROM transactions 
     WHERE status = 'completed'"
);
$stmtTransactionMetrics->execute();
$transactionMetrics = $stmtTransactionMetrics->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Performance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    
    <?php include "../../includes/cashier_sidebar.php"; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>
                <span class="material-symbols-outlined" style="vertical-align:middle">analytics</span>
                My Performance
            </h1>

        </div>

        <!-- Performance Metrics -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Performance Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h6>Total Served</h6>
                        <h4><?= $queueMetrics['total_served'] ?? 0 ?></h4>
                    </div>
                    <div class="col-md-4">
                        <h6>Avg Service Time</h6>
                        <h4><?= $queueMetrics['avg_service_time'] ? number_format($queueMetrics['avg_service_time'], 1) . 'm' : 'N/A' ?></h4>
                    </div>
                    <div class="col-md-4">
                        <h6>Total Revenue</h6>
                        <h4 class="text-success">₱<?= number_format($transactionMetrics['total_revenue'] ?? 0, 2) ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Metrics -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Financial Metrics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6">
                        <h6>Total Transactions</h6>
                        <h4><?= $transactionMetrics['total_transactions'] ?? 0 ?></h4>
                    </div>
                    <div class="col-md-6">
                        <h6>Total Revenue</h6>
                        <h4 class="text-success">₱<?= number_format($transactionMetrics['total_revenue'] ?? 0, 2) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "../../includes/footer.php"; ?>

    <script src="../../js/auto-refresh.js"></script>
</body>
</html>