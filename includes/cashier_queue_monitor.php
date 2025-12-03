<?php
require_once "../api/staff-api/open-live-queue-b.php";
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Live Queue Display - EDUQUEUE</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../css/common.css">
        <link rel="stylesheet" href="../css/open_live_queue.css">
    </head>

    <body style="background-color: #f0f8ff;">

<header class="header">
    <?php include __DIR__ . '../../includes/header.php'; ?>
    <?php include 'sidebar.php'; ?>

        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    
                    <div class="digital-display">
                        <div class="display-header">
                            <h3><i class="bi bi-display"></i> EDUQUEUE LIVE DISPLAY</h3>
                            <!-- <div class="auto-refresh-notice">
                                <i class="bi bi-arrow-clockwise"></i> Auto-refreshes every 15 seconds
                            </div> -->
                            <button class="btn btn-primary" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Refresh Display
                            </button>
                        </div>
                        
                        <!-- Now Serving Section -->
                        <div class="now-serving-box">
                            <div class="now-serving-label blink">
                                <i class="bi bi-megaphone"></i> NOW SERVING
                            </div>
                            
                            <?php if ($nowServing): ?>
                                <div class="queue-number flash">
                                    <?= $nowServing['queue_number'] ?>
                                </div>
                                <div class="student-info">
                                    <?= htmlspecialchars($nowServing['name']) ?>
                                </div>
                                <div class="student-info course">
                                    <?= htmlspecialchars($nowServing['course']) ?>
                                </div>
                                <div class="student-info id">
                                    ID: <?= htmlspecialchars($nowServing['student_id']) ?>
                                </div>
                            <?php else: ?>
                                <div class="queue-number" style="color: #808080ff;">
                                    No one is currently being served
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Next Serving Section -->
                        <div class="next-serving-box">
                            <div class="next-serving-label">
                                <i class="bi bi-chevron-right"></i> NEXT TO SERVE
                            </div>
                            
                            <?php if ($nextServing): ?>
                                <div class="next-queue-number">
                                    <?= $nextServing['queue_number'] ?>
                                </div>
                                <div class="student-info">
                                    <?= htmlspecialchars($nextServing['name']) ?>
                                </div>
                                <div class="student-info course">
                                    <?= htmlspecialchars($nextServing['course']) ?>
                                </div>
                                <div class="student-info id">
                                    ID: <?= htmlspecialchars($nextServing['student_id']) ?>
                                </div>
                            <?php else: ?>
                                <div class="next-queue-number" style="color: #808080ff;">
                                    No one is waiting
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Stats Row -->
                        <div class="stats-container">
                            <div class="stat-card" id="waiting-stat-card" style="cursor:pointer;">
                                <div class="stat-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number"><?= $waitingCount ?: 0 ?></div>
                                    <div class="stat-label">Waiting</div>
                                </div>
                            </div>
                            
                            <div class="stat-card" id="served-stat-card" style="cursor:pointer;">
                                <div class="stat-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number"><?= $servedCount ?: 0 ?></div>
                                    <div class="stat-label">Served</div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="bi bi-door-open"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number">3</div>
                                    <div class="stat-label">Counters Open</div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($recentServed)): ?>
                        <div class="recent-served">
                            <h6 id="recent-toggle" style="cursor:pointer;">
                                <i class="bi bi-clock-history"></i> RECENTLY SERVED
                            </h6>

                            <div class="recent-list" id="recent-list">
                                <?php foreach ($recentServed as $recent): ?>
                                    <div class="recent-item">
                                        <span class="recent-number">#<?= $recent['queue_number'] ?></span>
                                        <span class="recent-name"><?= htmlspecialchars($recent['name']) ?></span>
                                        <span class="recent-time"><?= date('h:i A', strtotime($recent['time_out'])) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Waiting List -->
                    <div class="waiting-list-container">
                        <div class="waiting-list" id="waiting-list" style="display: none;">
                            <h6>Total Waiting: <?= count($waitingList) ?></h6>
                            <ul>
                                <?php foreach ($waitingList as $waiting): ?>
                                    <li>
                                        <strong><?= htmlspecialchars($waiting['name']) ?></strong> -
                                        <?= htmlspecialchars($waiting['course']) ?> -
                                        ID: <?= htmlspecialchars($waiting['student_id']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <footer class="footer">
            <p>&copy; 2025 Saint Louis College of San Fernando, La Union. All rights reserved.</p>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/open_live_queue.js"></script>

    </body>
</html>
