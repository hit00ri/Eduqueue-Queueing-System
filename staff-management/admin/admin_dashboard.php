<?php
include "../../api/staff-api/admin/dashboard-queries.php";
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard - Queuing</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/common.css">
  <link rel="stylesheet" href="../../css/dashboard.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

</head>

<body>
  <?php include '../../includes/sidebar.php'; ?>

  <button class="dark-toggle" title="Toggle dark mode">
    <i class="bi bi-moon-stars"></i>
  </button>

  <div class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>
        <span class="material-symbols-outlined" style="vertical-align:middle">dashboard</span>
        Dashboard
      </h1>
    </div>

    <div class="container-fluid mb-4">
      <div class="row g-3">
        <?php
          // Prepare metrics with safe defaults
          $m = [
            ['label' => 'Total Users', 'value' => (int) ($total_users ?? 0), 'color' => 'primary'],
            ['label' => 'Total Students', 'value' => (int) ($total_students ?? 0), 'color' => 'success'],
            ['label' => 'Queue Today', 'value' => (int) ($total_queue_today ?? 0), 'color' => 'info'],
            ['label' => 'Waiting', 'value' => (int) ($waiting ?? 0), 'color' => 'warning'],
            ['label' => 'Served Today', 'value' => (int) ($served_today ?? 0), 'color' => 'secondary'],
            ['label' => 'Voided Today', 'value' => (int) ($voided_today ?? 0), 'color' => 'danger'],
            ['label' => 'Revenue Today', 'value' => '₱' . number_format((float) ($revenue_today ?? 0), 2), 'color' => 'dark'],
            ['label' => 'Transactions Today', 'value' => (int) ($transactions_today ?? 0), 'color' => 'primary'],
            ['label' => 'Avg Wait', 'value' => (round((float) ($avg_wait_today ?? 0),2) . 's'), 'color' => 'info'],
            ['label' => 'Avg Service', 'value' => (round((float) ($avg_service_today ?? 0),2) . 's'), 'color' => 'info'],
          ];

          foreach ($m as $metric): ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
              <div class="card shadow-sm h-100">
                <div class="card-body p-3">
                  <div class="d-flex flex-column">
                    <small class="text-muted"><?= htmlspecialchars($metric['label']) ?></small>
                    <div class="mt-2 d-flex align-items-end justify-content-between">
                      <h4 class="mb-0 text-<?= htmlspecialchars($metric['color']) ?>"><?= htmlspecialchars($metric['value']) ?></h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
      </div>
    </div>

    <h3 style="margin-left:20px;">Served vs Voided (Daily)</h3>
    <canvas id="servedVoidedChart" style="width:100%; max-height:400px;"></canvas>


  </div>



  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../../js/served_voided_chart.js"></script>
  <script src="../../js/darkmode.js"></script>
  <!-- <script src="../js/autorefresh.js"></script> -->

</body>

</html>