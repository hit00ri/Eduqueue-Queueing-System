<?php
require_once __DIR__ . '/../api/protect.php';
// load student feedback bootstrap (protect.php validates student session)
require_once "../api/student-api/feedback-b.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback - Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="../css/feedback.css">
    <link rel="stylesheet" href="../css/help.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <?php include '../includes/student_sidebar.php'; ?>
    
    <a href="help.php" class="help-button position-fixed top-10 end-0 m-3 pulse">
        <i class="bi bi-question-lg"></i>
    </a>

    <div class="main-content"  style="margin-top: 10px">
        
        <div class="feedback-container">
            <div class="card feedback-card fade-in">
                <div class="card-header feedback-header text-center">
                    <div class="feedback-icon mb-3">
                        <i class="bi bi-chat-heart display-4"></i>
                    </div>
                    <h2 class="h3 mb-2">Share Your Feedback</h2>
                    <p class="mb-0 opacity-75">Help us improve our queueing management system</p>
                </div>
                
                <div class="card-body p-4">
                    <!-- Error Message -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Success Message -->
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> 
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <div class="mt-2">
                                <a href="student_dashboard.php" class="btn btn-outline-success btn-sm">
                                    Return to Dashboard
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                    
                    <form method="post">
                        <!-- Rating Section -->
                        <div class="mb-4">
                            <label class="form-label h5">
                                <i class="bi bi-star-fill text-warning"></i>
                                How would you rate your experience?
                            </label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5">
                                <label for="star5">★</label>
                                
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4">★</label>
                                
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3">★</label>
                                
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2">★</label>
                                
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1">★</label>
                            </div>
                            <div class="text-center text-muted mt-2">
                                <small>1 = Poor, 5 = Excellent</small>
                            </div>
                        </div>

                        <!-- Recent Queues (Optional) -->
                        <div class="mb-4">
                            <label class="form-label h6">
                                <i class="bi bi-ticket-perforated"></i>
                                Which queue are you providing feedback for? (Optional)
                            </label>
                            <?php
                            // Get recent queues for this student
                            $recentQueues = $conn->prepare("
                                SELECT queue_id, queue_number, time_in 
                                FROM queue 
                                WHERE student_id = ? 
                                ORDER BY time_in DESC 
                                LIMIT 5
                            ");
                            $recentQueues->execute([$student['student_id']]);
                            $queues = $recentQueues->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            
                            <select name="queue_id" class="form-select">
                                <option value="">Select a recent queue (optional)</option>
                                <?php foreach ($queues as $queue): ?>
                                    <option value="<?= $queue['queue_id'] ?>">
                                        Queue #<?= $queue['queue_number'] ?> - 
                                        <?= date('M j, g:i A', strtotime($queue['time_in'])) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">
                                Selecting a queue helps us understand which service you're referring to.
                            </small>
                        </div>

                        <!-- Comments Section -->
                        <div class="mb-4">
                            <label for="comments" class="form-label h6">
                                <i class="bi bi-chat-left-text"></i>
                                Your Comments (Optional)
                            </label>
                            <textarea 
                                name="comments" 
                                id="comments" 
                                class="form-control" 
                                rows="5" 
                                placeholder="Tell us about your experience... What did you like? What can we improve?"
                                maxlength="500"
                            ></textarea>
                            <div class="character-count mt-1 text-end">
                                <span id="charCount">0</span>/500 characters
                            </div>
                        </div>

                        <!-- Feedback Tips -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-lightbulb"></i> Feedback Tips
                            </h6>
                            <ul class="mb-0 small">
                                <li>Be specific about what you liked or didn't like</li>
                                <li>Suggestions for improvement are welcome</li>
                                <li>Your feedback helps us serve you better</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" name="submit_feedback" class="btn btn-primary btn-lg">
                                <i class="bi bi-send-check"></i> Submit Feedback
                            </button>
                        </div>
                    </form>
                    
                    <?php endif; ?>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-4">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <i class="bi bi-shield-check display-6 text-primary"></i>
                                <h6 class="mt-2">Confidential</h6>
                                <small class="text-muted">Your feedback is anonymous and confidential</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <i class="bi bi-graph-up display-6 text-success"></i>
                                <h6 class="mt-2">Helps Improve</h6>
                                <small class="text-muted">We use feedback to enhance our services</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <i class="bi bi-clock display-6 text-info"></i>
                                <h6 class="mt-2">Quick Process</h6>
                                <small class="text-muted">Takes less than 2 minutes to complete</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    
    <?php include "../includes/footer.php"; ?>
    <!-- JavaScript -->
     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/feedback.js"></script>
</body>
</html>