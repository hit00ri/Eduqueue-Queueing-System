<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<link rel="stylesheet" href="/Eduqueue-Queueing-System/css/sidebar.css">

<div class="sidebar fade-in">
    <!-- <h4 class="mb-4 sidebar-title">
        <span class="material-symbols-outlined nav-icon">dashboard</span>
        Queuing System
    </h4> -->

    <!-- Debug: Show session status -->
    <?php if (getenv('APP_DEBUG') || isset($_GET['debug'])): ?>
    <div style="background:#f0f0f0; padding:8px; margin-bottom:10px; font-size:11px; color:#333;">
        Session: <?= isset($_SESSION['user']) ? 'YES' : 'NO' ?><br>
        Role: <?= $_SESSION['user']['role'] ?? 'N/A' ?><br>
        Name: <?= $_SESSION['user']['username'] ?? 'N/A' ?>
    </div>
    <?php endif; ?>

    <div class="admin-sidebar-profile">
        <h3 class="mb-4 sidebar-title">
            <span class="material-symbols-outlined">person_2</span>
        Admin
        </h3>
    </div>


    <a class="sidebar-link" href="/Eduqueue-Queueing-System/staff-management/admin/admin_dashboard.php">
        <span class="material-symbols-outlined nav-icon">monitoring</span>
        Dashboard
    </a>

    <!-- Performance Metrics (Admin) -->
    <a class="sidebar-link" href="/Eduqueue-Queueing-System/staff-management/admin/metrics_dashboard.php">
        <span class="material-symbols-outlined nav-icon">monitoring</span>
        Performance Metrics
    </a>
    
    <!-- Daily Reports -->
    <a class="sidebar-link" href="/Eduqueue-Queueing-System/staff-management/admin/reports.php">
        <span class="material-symbols-outlined nav-icon">analytics</span>
        Daily Reports
    </a>
    <?php if (getenv('APP_DEBUG') === '1'): ?>
        <a class="sidebar-debug" href="/Eduqueue-Queueing-System/staff-management/admin/reports.php?debug_protect=1" title="Debug protect">[dbg]</a>
    <?php endif; ?>

    <!-- System Logs (Admin) -->
    <a class="sidebar-link" href="/Eduqueue-Queueing-System/staff-management/admin/manage_user.php">
        <span class="material-symbols-outlined nav-icon">list_alt</span>
        Manage Users
    </a>
    <?php if (getenv('APP_DEBUG') === '1'): ?>
        <a class="sidebar-debug" href="/Eduqueue-Queueing-System/staff-management/admin/manage_user.php?debug_protect=1" title="Debug protect">[dbg]</a>
    <?php endif; ?>

    <a class="sidebar-link" href="/Eduqueue-Queueing-System/includes/transaction_history.php">
        <span class="material-symbols-outlined nav-icon">assessment</span>
        Transaction History
    </a>

    <div class="logout-container">
        <a class="sidebar-link confirm-logout" href="/Eduqueue-Queueing-System/api/auth.php?action=logout">
            <span class="material-symbols-outlined">logout</span>
            Logout
        </a>
    </div>
</div>

<script src="/Eduqueue-Queueing-System/js/sidebar.js"></script>
