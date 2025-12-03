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
        <a class="nav-link" href="profile.php">
        <span class="material-symbols-outlined">person_2</span>
        <h3>Admin</h3>
    </div>


    <a class="sidebar-link" href="/Eduqueue-Queueing-System/staff-management/admin/admin_dashboard.php">
        <span class="material-symbols-outlined nav-icon">monitoring</span>
        Dashboard
    </a>

    <!-- Performance Metrics (Admin) -->
    <a class="sidebar-link" href="/Eduqueue-Queueing-System/includes/metrics_dashboard.php">
        <span class="material-symbols-outlined nav-icon">monitoring</span>
        Performance Metrics
    </a>
    
    <!-- Daily Reports -->
    <a class="sidebar-link" href="/Eduqueue-Queueing-System/includes/reports.php">
        <span class="material-symbols-outlined nav-icon">analytics</span>
        Daily Reports
    </a>

    <!-- System Logs (Admin) -->
    <a class="sidebar-link" href="/Eduqueue-Queueing-System/staff-management/admin/manage_user.php">
        <span class="material-symbols-outlined nav-icon">list_alt</span>
        Manage Users
    </a>
    
    <!-- Advanced Reports (Admin) -->
    <a class="sidebar-link" href="/Eduqueue-Queueing-System/includes/reports.php">
        <span class="material-symbols-outlined nav-icon">assessment</span>
        Monitoring Dashboard
    </a>

    <a class="sidebar-link" href="/Eduqueue-Queueing-System/includes/transaction_history.php">
        <span class="material-symbols-outlined nav-icon">assessment</span>
        Transaction History
    </a>

    <div class="logout-container">
        <a class="sidebar-link" href="../../api/student-api/student-logout-b.php">
            <span class="material-symbols-outlined">logout</span>
            Logout
        </a> 
    </div>
</div>

<script src="/Eduqueue-Queueing-System/js/sidebar.js"></script>
