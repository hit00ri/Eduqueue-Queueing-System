<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<div class="sidebar fade-in">
    <h4 class="mb-4 sidebar-title">
        <span class="material-symbols-outlined nav-icon">dashboard</span>
        Queuing System
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
        <span class="material-symbols-outlined nav-icon">record_voice_over</span>
        Queue Management
    </a>
    <?php endif; ?>

</div>