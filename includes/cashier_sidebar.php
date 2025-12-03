<?php
require_once "../../api/staff-api/cashier/open-live-queue-C-b.php";
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<link rel="stylesheet" href="../../css/sidebar.css">

<div class="sidebar fade-in">
    <h4 class="mb-4 sidebar-title">
        <span class="material-symbols-outlined nav-icon">dashboard</span>
        Queueing System
    </h4>

    <!-- Debug: Show session status -->
    <?php if (getenv('APP_DEBUG') || isset($_GET['debug'])): ?>
    <div style="background:#f0f0f0; padding:8px; margin-bottom:10px; font-size:11px; color:#333;">
        Session: <?= isset($_SESSION['user']) ? 'YES' : 'NO' ?><br>
        Role: <?= $_SESSION['user']['role'] ?? 'N/A' ?><br>
        Name: <?= $_SESSION['user']['username'] ?? 'N/A' ?>
    </div>
    <?php endif; ?>


    <!-- Cashier & Admin: Queue Management -->
    <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['cashier'])): ?>
    <a class="sidebar-link" href="dashboard.php">
        <span class="material-symbols-outlined">featured_play_list</span>
        Queue Management
    </a>

    <a class="sidebar-link" href="performance.php">
        <span class="material-symbols-outlined">analytics</span>
        My Performance
    </a>

    <a class="sidebar-link" href="logs.php">
        <span class="material-symbols-outlined">update</span>
       Activity Logs
    </a>

    <a class="sidebar-link" href="cashier_queue_monitor.php">
        <span class="material-symbols-outlined">monitor</span>
       View Live queue
    </a>

    <div class="logout-container">
        <a class="sidebar-link" href="../../api/student-api/student-logout-b.php">
            <span class="material-symbols-outlined">logout</span>
            Logout
        </a>
    </div>
    <?php endif; ?>

</div>

<script src="../../js/sidebar.js"></script>