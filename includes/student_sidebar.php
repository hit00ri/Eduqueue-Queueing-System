<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<link rel="stylesheet" href="../css/sidebar.css">

<div class="sidebar fade-in">
    <h4 class="mb-4 sidebar-title">
        <span class="material-symbols-outlined nav-icon">dashboard</span>
        Student
    </h4>

    <!-- Debug: Show session status -->
    <?php if (getenv('APP_DEBUG') || isset($_GET['debug'])): ?>
        <div style="background:#f0f0f0; padding:8px; margin-bottom:10px; font-size:11px; color:#333;">
            Session: <?= isset($_SESSION['user']) ? 'YES' : 'NO' ?><br>
            Role: <?= $_SESSION['user']['role'] ?? 'N/A' ?><br>
            Name: <?= $_SESSION['user']['username'] ?? 'N/A' ?>
        </div>
    <?php endif; ?>

    <div>
        <a class="nav-link" href="profile.php">
            <span class="material-symbols-outlined">person</span>
            <h2>Profile</h2>
        </a>
    </div>

    <a class="sidebar-link" href="../student-management/student_dashboard.php">
        <span class="material-symbols-outlined">dashboard</span>
        Dashboard
    </a>

    <a class="sidebar-link" href="../student-management/payment_slip.php">
        <span class="material-symbols-outlined">receipt</span>
        Payment Slip
    </a>

    <a class="sidebar-link" href="../student-management/queue_history.php">
        <span class="material-symbols-outlined">history</span>
        History
    </a>

    <!-- <a class="sidebar-link" href="../student-management/help.php">
        <span class="material-symbols-outlined nav-icon">help</span>
        Help
    </a> -->

    <div class="logout-container" style="margin-bottom: 20px;">
        <a class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'feedback.php' ? 'active' : '' ?>"
            href="feedback.php">
            <span class="material-symbols-outlined nav-icon">feedback</span>
            Feedback
        </a>

        <a class="sidebar-link confirm-logout" href="/Eduqueue-Queueing-System/api/auth.php?action=logout">
            <span class="material-symbols-outlined">logout</span>
            Logout
        </a>
    </div>
</div>

<script src="../js/sidebar.js"></script>

