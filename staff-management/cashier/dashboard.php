<?php
  require_once __DIR__ . '/../../api/protect.php';
  // load cashier dashboard bootstrap (protect.php already loads db/config.php and validates session)
  require_once "../../api/staff-api/cashier/dashboard-b.php";

  // Check if we have a payment success/error message
  $paymentMessage = '';
  $paymentType = ''; // success, error, info
  if (isset($_SESSION['payment_message'])) {
    $paymentMessage = $_SESSION['payment_message'];
    $paymentType = $_SESSION['payment_type'] ?? 'info';
    unset($_SESSION['payment_message']);
    unset($_SESSION['payment_type']);
  }

  // Get the current user ID for handling payments
  $currentUserId = $_SESSION['user']['user_id'] ?? null;
?>

<!doctype html>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard - Queuing</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../css/common.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/dashboard.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <!-- Add Font Awesome for payment icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  </head>

  <body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <?php include '../../includes/cashier_sidebar.php'; ?>

    <div class="main-content">

      <!-- Show payment message if exists -->
      <?php if ($paymentMessage): ?>
        <div class="alert alert-<?= $paymentType ?> alert-dismissible fade show mb-4" role="alert">
          <?= htmlspecialchars($paymentMessage) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Dashboard Header -->
      <div class="dashboard-header mb-5">
        <div class="header-content">
          <div>
            <h1 class="header-title">
              <span class="material-symbols-outlined">dashboard</span>
              Queue Management
            </h1>
            <p class="header-subtitle">Manage and serve customers efficiently</p>
          </div>
          <!-- Add quick stats badge -->
          <div class="header-stats">
            <span class="badge bg-info">
              <i class="fas fa-users me-1"></i> Waiting: <?= count($waiting) ?>
            </span>
          </div>
        </div>
      </div>

      <!-- Quick Stats Dashboard -->
      <div class="stats-dashboard">
        <!-- Primary Stat: Now Serving -->
        <div class="primary-stat">
          <div class="stat-content">
            <div class="stat-icon serving-icon">
              <span class="material-symbols-outlined">record_voice_over</span>
            </div>
            <div class="stat-info">
              <span class="stat-label">Currently Serving</span>
              <div class="stat-display">
                <span class="stat-number">#<?= ($serving['queue_number'] ?? '-') ?></span>
                <span class="stat-name"><?= ($serving ? htmlspecialchars($serving['name']) : 'Idle') ?></span>
              </div>
              <?php if ($serving): ?>
                <div class="payment-info">
                  <small class="text-muted">
                    <i class="fas fa-money-bill-wave me-1"></i>
                    Amount: ₱<?= number_format($serving['amount'] ?? 0, 2) ?>
                  </small>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="stat-action">
            <?php if ($serving): ?>
              <div class="action-buttons">
                <button class="action-btn success-btn" data-bs-toggle="modal" data-bs-target="#paymentModal"
                  data-queue-id="<?= $serving['queue_id'] ?>" data-student-name="<?= htmlspecialchars($serving['name']) ?>"
                  data-amount="<?= $serving['amount'] ?? 0 ?>">
                  <i class="fas fa-money-bill-wave me-2"></i>
                  Process Payment
                </button>
                <form method="post" class="form-inline">
                  <input type="hidden" name="queue_id" value="<?= $serving['queue_id'] ?>">
                  <button name="served" class="action-btn secondary-btn"
                    onclick="return confirm('Mark as served without payment?')">
                    <span class="material-symbols-outlined">task_alt</span>
                    Served
                  </button>
                </form>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Secondary Stats -->
        <div class="secondary-stats">
          <!-- Queue Count -->
          <div class="secondary-stat">
            <div class="stat-icon queue-icon">
              <span class="material-symbols-outlined">schedule</span>
            </div>
            <div class="stat-content-vertical">
              <span class="stat-label">In Queue</span>
              <span class="stat-number"><?= count($waiting) ?: 0 ?></span>
              <span
                class="stat-meta"><?= count($waiting) === 1 ? '1 customer waiting' : count($waiting) . ' customers' ?></span>
            </div>
          </div>

          <!-- Call Next Button -->
          <form method="post" class="button-form">
            <button name="call_next" class="call-next-btn">
              <span class="material-symbols-outlined">notifications_active</span>
              <span class="btn-text">
                <strong>Call Next</strong>
                <small>Serve next customer</small>
              </span>
            </button>
          </form>

          <!-- Void Action -->
          <form method="post" class="button-form">
            <input type="hidden" name="queue_id" value="<?= $serving['queue_id'] ?? '' ?>">
            <button name="voided" class="void-btn" <?= !$serving ? 'disabled' : '' ?>>
              <span class="material-symbols-outlined">cancel</span>
              <span class="btn-text">
                <strong>Void</strong>
                <small>Skip this queue</small>
              </span>
            </button>
          </form>
        </div>
      </div>

      <!-- Queue Management Section -->
      <div class="queue-management">
        <!-- Now Serving Panel -->
        <div class="serving-panel">
          <div class="panel-header">
            <h3 class="panel-title">
              <span class="material-symbols-outlined">visibility</span>
              Now Serving
            </h3>
            <span class="panel-badge"><?= ($serving ? 'Active' : 'Idle') ?></span>
          </div>

          <?php if ($serving): ?>
            <div class="serving-display">
              <div class="queue-number-display">#<?= $serving['queue_number'] ?></div>
              <div class="queue-details">
                <div class="detail-field">
                  <span class="detail-label">Customer Name</span>
                  <span class="detail-value"><?= htmlspecialchars($serving['name']) ?></span>
                </div>
                <?php if ($serving['payment_for']): ?>
                  <div class="detail-field">
                    <span class="detail-label">Payment For</span>
                    <span class="detail-value"><?= htmlspecialchars($serving['payment_for']) ?></span>
                  </div>
                <?php endif; ?>
                <div class="detail-field">
                  <span class="detail-label">Amount Due</span>
                  <span class="detail-value text-success fw-bold">₱<?= number_format($serving['amount'] ?? 0, 2) ?></span>
                </div>
              </div>
              <div class="action-buttons">
                <button class="btn-action btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal"
                  data-queue-id="<?= $serving['queue_id'] ?>" data-student-name="<?= htmlspecialchars($serving['name']) ?>"
                  data-amount="<?= $serving['amount'] ?? 0 ?>">
                  <i class="fas fa-money-bill-wave me-2"></i>
                  Process Payment
                </button>
                <form method="post" class="action-form">
                  <input type="hidden" name="queue_id" value="<?= $serving['queue_id'] ?>">
                  <button name="served" class="btn-action btn-secondary">
                    <span class="material-symbols-outlined">task_alt</span>
                    Mark as Served
                  </button>
                </form>
                <form method="post" class="action-form">
                  <input type="hidden" name="queue_id" value="<?= $serving['queue_id'] ?>">
                  <button name="voided" class="btn-action btn-warning">
                    <span class="material-symbols-outlined">cancel</span>
                    Void Queue
                  </button>
                </form>
              </div>
            </div>
          <?php else: ?>
            <div class="empty-state-panel">
              <div class="empty-icon">
                <span class="material-symbols-outlined">mood</span>
              </div>
              <p class="empty-text">No customers being served</p>
              <p class="empty-hint">Click "Call Next" to serve a customer</p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Waiting List Section -->
        <div class="waiting-list-section">
          <h3 class="section-title">
            <span class="material-symbols-outlined">queue</span>
            Waiting List (<?= count($waiting) ?: 0 ?>)
          </h3>

          <div class="waiting-list-container">
            <?php if (empty($waiting)): ?>
              <div class="empty-state text-center text-muted py-5">
                <span class="material-symbols-outlined" style="font-size: 48px;">check_circle</span>
                <p class="mt-3 mb-0">All caught up!</p>
                <small>No one waiting</small>
              </div>
            <?php else: ?>
              <ul class="waiting-list">
                <?php foreach ($waiting as $w): ?>
                  <li class="waiting-item">
                    <div class="waiting-number">#<?= $w['queue_number'] ?></div>
                    <div class="waiting-info">
                      <div class="waiting-name"><?= htmlspecialchars($w['name']) ?></div>
                      <div class="waiting-details">
                        <small class="text-muted">
                          <i class="fas fa-money-bill-wave me-1"></i>
                          ₱<?= number_format($w['amount'] ?? 0, 2) ?>
                        </small>
                        <small class="text-muted ms-3">
                          <i class="fas fa-clock me-1"></i>
                          <?= date('H:i', strtotime($w['time_in'])) ?>
                        </small>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="paymentModalLabel">
              <i class="fas fa-money-bill-wave me-2"></i>Process Payment
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <!-- Simple form that posts back to the same page -->
          <form method="POST" action="">
            <input type="hidden" name="action" value="complete_payment">
            <input type="hidden" name="queue_id" id="modalQueueId">
            <div class="modal-body">
              <div id="paymentDetails">
                <div class="mb-3">
                  <label class="form-label fw-bold">Student</label>
                  <input type="text" class="form-control" id="modalStudentName" readonly>
                </div>
                <div class="mb-3">
                  <label for="paymentFor" class="form-label fw-bold">Payment For</label>
                  <select class="form-select" id="paymentFor" name="payment_for" required>
                    <option value="Tuition Fee" selected>Tuition Fee</option>
                    <option value="Miscellaneous Fee">Miscellaneous Fee</option>
                    <option value="Library Fee">Library Fee</option>
                    <option value="Laboratory Fee">Laboratory Fee</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="amount" class="form-label fw-bold">Amount (PHP)</label>
                  <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" placeholder="0.00"
                      required min="0">
                  </div>
                </div>
                <div class="mb-3">
                  <label for="paymentType" class="form-label fw-bold">Payment Type</label>
                  <select class="form-select" id="paymentType" name="payment_type" required>
                    <option value="">Select payment type</option>
                    <option value="cash">Cash</option>
                    <option value="card">Credit/Debit Card</option>
                    <option value="digital">Digital Payment</option>
                  </select>
                </div>
                <div class="alert alert-info">
                  <small>
                    <i class="fas fa-info-circle me-1"></i>
                    This will create a transaction record and mark the queue as served.
                  </small>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i>Cancel
              </button>
              <button type="submit" class="btn btn-success">
                <i class="fas fa-check me-1"></i>Complete Payment
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/darkmode.js"></script>
    <script src="../../js/auto-refresh.js"></script>
    <script src="../../js/session-guard.js"></script>
    <script>
      // Payment Modal Handler - Simple version
      const paymentModal = document.getElementById('paymentModal');
      paymentModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const queueId = button.getAttribute('data-queue-id');
        const studentName = button.getAttribute('data-student-name');
        const amount = button.getAttribute('data-amount');

        document.getElementById('modalQueueId').value = queueId;
        document.getElementById('modalStudentName').value = studentName;
        document.getElementById('amount').value = amount;
      });

      // Auto-refresh dashboard every 30 seconds
      setTimeout(() => {
        location.reload();
      }, 30000);
    </script>

    <?php include "../../includes/footer.php"; ?>
  </body>

</html>