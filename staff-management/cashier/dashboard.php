<?php
require_once __DIR__ . '/../../db/config.php';
require_once __DIR__ . '/../../api/auth.php';
// api/auth.php already ensures login

require_once "../../api/staff-api/cashier/dashboard-b.php";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - Queuing</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../../css/common.css?v=<?= time() ?>">
<link rel="stylesheet" href="../../css/dashboard.css?v=<?= time() ?>">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

</head>
<body>
<?php include __DIR__ . '/../../includes/header.php'; ?>

<?php include '../../includes/cashier_sidebar.php'; ?>

<div class="main-content">

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
        </div>
      </div>
      <div class="stat-action">
        <form method="post" class="form-inline">
          <input type="hidden" name="queue_id" value="<?= $serving['queue_id'] ?? '' ?>">
          <button name="served" class="action-btn success-btn" <?= !$serving ? 'disabled' : '' ?>>
            <span class="material-symbols-outlined">task_alt</span>
            Served
          </button>
        </form>
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
          <span class="stat-meta"><?= count($waiting) === 1 ? '1 customer waiting' : count($waiting) . ' customers' ?></span>
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
          </div>
          <div class="action-buttons">
            <form method="post" class="action-form">
              <input type="hidden" name="queue_id" value="<?= $serving['queue_id'] ?>">
              <button name="served" class="btn-action btn-success">
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
                  <div class="waiting-time"><?= $w['time_in'] ?? 'N/A' ?></div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<script src="../../js/darkmode.js"></script>
<script src="../../js/auto-refresh.js"></script>

<?php include "../../includes/footer.php"; ?>

</body>
</html>
