<?php
require_once __DIR__ . '/../api/student-protect.php';
require_once "../api/student-api/student-dashboard-b.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard - Queue System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="../css/help.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    

    <a href="help.php" class="help-button position-fixed top-0 end-0 m-3 pulse">
        <i class="bi bi-question-lg"></i>
    </a>

    <p class="mt-3"><a href="../api/student-api/student-logout-b.php">Logout</a></p>
<div class="student-dashboard-container">
    <div class="container-fluid">

        <?php include '../includes/student_sidebar.php'; ?>

        <div class="student-box card fade-in" style="margin-top: 10px; margin-left: 400px">
            <!-- Header -->
            <div class="text-center mb-4">
                <i class="bi bi-person-circle display-1 text-primary"></i>
                <h1 class="h3 mt-2">Welcome, <?= htmlspecialchars($student['name']) ?></h1>
                <p class="text-muted">Queueing Management System</p>
            </div>

            <!-- Success Message -->
            <?php if ($message): ?>
                <div class="alert alert-info alert-dismissible fade show">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Payment Slip Modal -->
            <div class="modal fade" id="paymentSlipModal" tabindex="-1" aria-labelledby="paymentSlipModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentSlipModalLabel">
                                <i class="bi bi-receipt"></i> Payment Slip
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="payment-slip-container">
                                <!-- Header -->
                                <div class="payment-slip-header text-center">
                                    <h1 class="h3 mb-1">Saint Louis College</h1>
                                    <p class="mb-1">City of San Fernando, 2500 La Union</p>
                                    <h2 class="h4 mb-0">PAYMENT SLIP</h2>
                                </div>

                                <!-- Student Information -->
                                <div class="form-section">
                                    <h5 class="mb-3"><i class="bi bi-person-badge"></i> Student Information</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>NAME:</strong></label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>ID NO:</strong></label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($student['student_id']) ?>" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>COURSE & YEAR:</strong></label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($student['course'] ?? '') ?> - <?= htmlspecialchars($student['year_level'] ?? '') ?>" readonly>
                                    </div>
                                </div>

                                <!-- Payment Details -->
                                <div class="form-section">
                                    <h5 class="mb-3"><i class="bi bi-cash-coin"></i> Payment Details</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>AMOUNT:</strong></label>
                                        <div class="input-group amount-input">
                                            <span class="input-group-text">₱</span>
                                            <input type="text" class="form-control" value="<?= isset($paymentSlipData) ? number_format($paymentSlipData['amount'], 2) : '0.00' ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><strong>IN PAYMENT OF:</strong></label>
                                        <div class="payment-options">
                                            <?php if (isset($paymentSlipData) && !empty($paymentSlipData['payment_for'])): ?>
                                                <?php
                                                $paymentForLabels = [
                                                    'tuition' => 'Tuition Fee',
                                                    'transcript' => 'Transcript',
                                                    'overdue' => 'Overdue',
                                                    'others' => 'Others'
                                                ];
                                                
                                                foreach ($paymentSlipData['payment_for'] as $paymentType): 
                                                    $label = $paymentForLabels[$paymentType] ?? ucfirst($paymentType);
                                                    $checked = in_array($paymentType, $paymentSlipData['payment_for']) ? 'checked' : '';
                                                ?>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" <?= $checked ?> disabled>
                                                        <label class="form-check-label">
                                                            <?= htmlspecialchars($label) ?>
                                                            <?php if ($paymentType === 'others' && !empty($paymentSlipData['other_purpose'])): ?>
                                                                : <?= htmlspecialchars($paymentSlipData['other_purpose']) ?>
                                                            <?php endif; ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="text-muted">No payment information available</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div class="mb-3">
                                    <label class="form-label"><strong>DATE:</strong></label>
                                    <input type="text" class="form-control" value="<?= date('F j, Y') ?>" readonly>
                                </div>

                                <!-- Reference Code -->
                                <div class="reference-code mt-4 pt-3 border-top">
                                    <div class="row">
                                        <div class="col-4">
                                            <strong>Reference Code</strong><br>
                                            FM-TREA-001
                                        </div>
                                        <div class="col-4">
                                            <strong>Revision No.</strong><br>
                                            0
                                        </div>
                                        <div class="col-4">
                                            <strong>Effectivity Date</strong><br>
                                            August 1, 2019
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Queue Information (only shows when a queue request is done).-->
            <?php if ($myQueueData): ?>
                <div class="alert alert-primary">
                    <h5 class="alert-heading">
                        <i class="bi bi-ticket-perforated"></i> Your Queue
                    </h5>
                    <div class="queue-number">#<?= $myQueueData['queue_number'] ?></div>
                    <div class="text-center">
                        <span class="badge status-badge 
                            <?= $myQueueData['status'] === 'serving' ? 'bg-success' : 'bg-warning' ?>">
                            Status: <?= strtoupper($myQueueData['status']) ?>
                        </span>
                    </div>
                    
                    <?php if ($myQueueData['status'] === 'waiting' && $queuePosition): ?>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Your position in queue: <strong><?= $queuePosition ?></strong>
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($myQueueData['status'] === 'serving'): ?>
                        <div class="text-center mt-2">
                            <div class="badge bg-success">
                                <i class="bi bi-megaphone"></i> Please proceed to the counter!
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Show Payment Slip Button in Your Queue Section -->
                    <!-- ALWAYS show if payment slip data exists, regardless of queue status -->
                    <?php if (isset($paymentSlipData) && !empty($paymentSlipData)): ?>
                        <div class="queue-actions text-center mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#paymentSlipModal">
                                <i class="bi bi-receipt"></i> Show Payment Slip
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Now Serving Section -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-broadcast"></i> Now Serving
                    </h5>
                </div>
                <div class="card-body text-center">
                    <?php if ($nowServing): ?>
                        <div class="display-6 text-primary fw-bold">
                            #<?= $nowServing['queue_number'] ?>
                        </div>
                        <p class="card-text"><?= htmlspecialchars($nowServing['name']) ?></p>
                    <?php else: ?>
                        <div class="text-muted">
                            <i class="bi bi-info-circle"></i> No one is currently being served
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Queue Statistics -->
            <div class="stats-box">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h4 text-primary"><?= $waitingCount ?: 0 ?></div>
                        <small class="text-muted">Waiting</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-success"><?= $servedCount ?: 0 ?></div>
                        <small class="text-muted">Served Today</small>
                    </div>
                </div>
            </div>

            <!-- Take Queue Button (only show if no active queue) -->
            <?php if (!$myQueueData || $myQueueData['status'] === 'served'): ?>
                <div class="text-center">
                    <?php if (isset($paymentSlipData) && (!isset($_SESSION['queue_created_after_payment']) || !$_SESSION['queue_created_after_payment'])): ?>
                        <!-- Show form to create queue with existing payment slip -->
                        <form method="post">
                            <button type="submit" name="take_queue" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> Take Queue Number with Payment Slip
                            </button>
                        </form>
                        <small class="text-muted d-block mt-2">
                            Your payment slip information will be used for your queue.
                        </small>
                    <?php else: ?>
                        <!-- Show link to payment slip page -->
                        <!-- <a href="payment_slip.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-ticket-perforated"></i> Take Queue Number
                        </a> -->
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Edit Payment Slip Button (if payment slip exists but queue not created) -->
            <?php if (isset($paymentSlipData) && (!isset($_SESSION['queue_created_after_payment']) || !$_SESSION['queue_created_after_payment'])): ?>
                <div class="text-center mt-2">
                    <a href="payment_slip.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil"></i> Edit Payment Slip
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
    <?php include "../includes/footer.php"; ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/auto-refresh.js"></script>
</body>
</html>