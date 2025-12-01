<?php
require_once "../api/student-api/payment-slip-b.php";    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Slip - Saint Louis College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="../css/slip.css">
    <link rel="stylesheet" href="../css/help.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <?php include '../includes/student_sidebar.php'; ?>
    
    <a href="help.php" class="help-button position-fixed top-0 end-0 m-3 pulse">
        <i class="bi bi-question-lg"></i>
    </a>

    <div class="container mt-4">
        <div class="payment-slip-container">
            <!-- Header -->
            <div class="payment-slip-header text-center">
                <h1 class="h3 mb-1">Saint Louis College</h1>
                <p class="mb-1">City of San Fernando, 2500 La Union</p>
                <h2 class="h4 mb-0">PAYMENT SLIP</h2>
            </div>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Payment Slip Form -->
            <form method="post">
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
                        <label for="amount" class="form-label"><strong>AMOUNT:</strong></label>
                        <div class="input-group amount-input">
                            <span class="input-group-text">₱</span>
                            <?php
                            // Get amount from POST or session
                            $amountValue = $_POST['amount'] ?? ($_SESSION['payment_slip']['amount'] ?? '');
                            ?>
                            <input type="number" 
                                   class="form-control" 
                                   id="amount" 
                                   name="amount" 
                                   step="0.01" 
                                   min="0.01" 
                                   required
                                   value="<?= htmlspecialchars($amountValue) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>IN PAYMENT OF:</strong></label>
                        <div class="payment-options">
                            <?php
                            // Get payment_for from POST or session
                            $paymentFor = $_POST['payment_for'] ?? ($_SESSION['payment_slip']['payment_for'] ?? []);
                            $otherPurpose = $_POST['other_purpose'] ?? ($_SESSION['payment_slip']['other_purpose'] ?? '');
                            ?>
                            
                            <div class="form-check">
                                <input type="checkbox" name="payment_for[]" value="tuition" class="form-check-input"
                                    <?= (in_array('tuition', $paymentFor)) ? 'checked' : '' ?>>
                                <label class="form-check-label">Tuition Fee</label>
                            </div>
                            
                            <div class="form-check">
                                <input type="checkbox" name="payment_for[]" value="transcript" class="form-check-input"
                                    <?= (in_array('transcript', $paymentFor)) ? 'checked' : '' ?>>
                                <label class="form-check-label">Transcript</label>
                            </div>
                            
                            <div class="form-check">
                                <input type="checkbox" name="payment_for[]" value="overdue" class="form-check-input"
                                    <?= (in_array('overdue', $paymentFor)) ? 'checked' : '' ?>>
                                <label class="form-check-label">Overdue</label>
                            </div>
                            
                            <div class="form-check">
                                <input type="checkbox" name="payment_for[]" value="others" class="form-check-input" id="others-checkbox"
                                    <?= (in_array('others', $paymentFor)) ? 'checked' : '' ?>>
                                <label class="form-check-label">Others (Please specify)</label>
                            </div>
                            
                            <input type="text" 
                                   class="form-control mt-2" 
                                   id="other-purpose" 
                                   name="other_purpose" 
                                   placeholder="Specify other purpose"
                                   value="<?= htmlspecialchars($otherPurpose) ?>"
                                   style="display: <?= (in_array('others', $paymentFor)) ? 'block' : 'none' ?>;">
                        </div>
                    </div>
                </div>

                <!-- Date -->
                <div class="mb-3">
                    <label class="form-label"><strong>DATE:</strong></label>
                    <input type="text" class="form-control" value="<?= date('F j, Y') ?>" readonly>
                </div>

                <!-- Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" name="submit_payment_slip" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i> Submit Payment Slip & Proceed to Queue
                    </button>
                    <a href="student_dashboard.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </form>

            <!-- Reference Code -->
            <div class="reference-code">
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

    <?php include "../includes/footer.php"; ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/darkmode.js"></script>
    <script src="../js/slip.js"></script>
</body>
</html>