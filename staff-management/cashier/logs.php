<?php
require_once __DIR__ . '/../../api/protect.php';
// logs page uses $conn from db/config.php via protect.php

$stmtQueue = $conn->prepare("SELECT queue_id, queue_number, status, time_in, time_out FROM queue ORDER BY time_in DESC");
$stmtQueue->execute();
$queueLogs = $stmtQueue->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>

<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <?php include "../../includes/cashier_sidebar.php"; ?>

    <div class="main-content" style="margin-left: 240px">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>
                <span class="material-symbols-outlined" style="vertical-align:middle">update</span>
                Activity Logs
            </h1>

        </div>

        <!-- Option 1: Logs from Queue Table -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Queue Logs</h5>
            </div>
            <div class="card-body">
                <?php if (count($queueLogs) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Queue ID</th>
                                    <th>Queue Number</th>
                                    <th>Status</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($queueLogs as $log): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($log['queue_id']) ?></td>
                                        <td><?= htmlspecialchars($log['queue_number']) ?></td>
                                        <td><?= htmlspecialchars($log['status']) ?></td>
                                        <td><?= htmlspecialchars($log['time_in']) ?></td>
                                        <td><?= htmlspecialchars($log['time_out']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-receipt" style="font-size: 3rem; color: #6c757d;"></i>
                        <p class="text-muted mt-2">No queue logs found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include "../../includes/footer.php"; ?>

    <script src="../../js/auto-refresh.js"></script>
</body>

</html>