<?php
// queue_history.php
require_once "../api/student-api/queue-history-b.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Queue History - Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="../css/queue_history.css">
</head>
<body>
    <?php include '../includes/student_sidebar.php'; ?>
    
    <!-- Dark Mode Toggle -->
    <button class="dark-toggle btn btn-outline-secondary position-fixed top-0 end-0 m-3" style="z-index: 1050;">
        <i class="bi bi-moon-stars"></i>
    </button>

    <div class="main-content">
        <div class="history-container">
            <!-- Student Info Header -->
            <div class="student-info-card">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1">
                            <i class="bi bi-person-circle"></i>
                            <?= htmlspecialchars($student['name']) ?>
                        </h4>
                        <p class="mb-0 opacity-75">
                            ID: <?= htmlspecialchars($student['student_id']) ?> | 
                            <?= htmlspecialchars($student['course'] ?? '') ?> - <?= htmlspecialchars($student['year_level'] ?? '') ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="student_dashboard.php" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">
                    <i class="bi bi-clock-history text-primary"></i>
                    My Queue History
                </h2>
                <div class="text-muted">
                    <small>Showing your last <?= count($queueHistory) ?> queue transactions</small>
                </div>
            </div>

            <div class="card fade-in shadow-sm">
                <div class="card-body p-0">
                    <?php if (empty($queueHistory)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox display-1 opacity-50"></i>
                            <h4 class="mt-3">No Queue History Found</h4>
                            <p class="mb-4">You haven't joined any queues yet.</p>
                            <a href="payment_slip.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Get Your First Queue Number
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="100">Queue #</th>
                                        <th width="120">Date</th>
                                        <th width="100">Time In</th>
                                        <th width="100">Time Out</th>
                                        <th width="120">Amount</th>
                                        <th>Purpose</th>
                                        <th width="100">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($queueHistory as $queue): ?>
                                        <tr>
                                            <td>
                                                <strong class="text-primary">#<?= $queue['queue_number'] ?></strong>
                                            </td>
                                            <td>
                                                <small><?= date('M j, Y', strtotime($queue['time_in'])) ?></small>
                                            </td>
                                            <td>
                                                <small><?= date('g:i A', strtotime($queue['time_in'])) ?></small>
                                            </td>
                                            <td>
                                                <?php if ($queue['time_out']): ?>
                                                    <small class="text-success"><?= date('g:i A', strtotime($queue['time_out'])) ?></small>
                                                <?php else: ?>
                                                    <small class="text-muted">-</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($queue['amount'] && $queue['amount'] > 0): ?>
                                                    <strong class="text-success">₱<?= number_format($queue['amount'], 2) ?></strong>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small><?= htmlspecialchars($queue['payment_for'] ?? 'General Inquiry') ?></small>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    <?= $queue['status'] === 'served' ? 'bg-success' : 
                                                       ($queue['status'] === 'serving' ? 'bg-primary' : 
                                                       ($queue['status'] === 'waiting' ? 'bg-warning' : 'bg-secondary')) ?>">
                                                    <?= ucfirst($queue['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Summary Stats -->
                        <div class="p-3 bg-light border-top">
                            <div class="row text-center">
                                <div class="col-6 col-md-3 mb-2 mb-md-0">
                                    <div class="h5 text-primary mb-1"><?= $totalQueues ?></div>
                                    <small class="text-muted">Total Queues</small>
                                </div>
                                <div class="col-6 col-md-3 mb-2 mb-md-0">
                                    <div class="h5 text-success mb-1"><?= $completedQueues ?></div>
                                    <small class="text-muted">Completed</small>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="h5 text-warning mb-1"><?= $waitingQueues ?></div>
                                    <small class="text-muted">Waiting</small>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="h5 text-info mb-1"><?= $servingQueues ?></div>
                                    <small class="text-muted">Serving</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Additional Info -->
            <?php if (!empty($queueHistory)): ?>
                <div class="mt-3">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle"></i> About Your Queue History
                        </h6>
                        <p class="mb-0 small">
                            This page shows only <strong>your personal queue history</strong>. You can see all the queues you've taken, 
                            their status, and payment details. The data is filtered to show only transactions associated with your student account.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/darkmode.js"></script>
</body>
</html>