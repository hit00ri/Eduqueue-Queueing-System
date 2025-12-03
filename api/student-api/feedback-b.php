<?php
// feedback-b.php
    require_once __DIR__ . "/../../db/config.php";

    if (!isset($_SESSION['student'])) {
        header("Location: /eduqueue-queueing-system/student-management/student_login.php");
        exit;
    }

    $student = $_SESSION['student'];
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
        $rating = $_POST['rating'] ?? 0;
        $comments = trim($_POST['comments'] ?? '');
        $queue_id = $_POST['queue_id'] ?? null;
        
        if ($rating < 1 || $rating > 5) {
            $error = "Please provide a rating between 1-5 stars.";
        } else {
            try {
                $stmt = $conn->prepare("
                    INSERT INTO student_feedback 
                    (student_id, queue_id, rating, comments, created_at) 
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$student['student_id'], $queue_id, $rating, $comments]);
                $success = "Thank you for your feedback!";
            } catch (PDOException $e) {
                $error = "Failed to submit feedback: " . $e->getMessage();
            }
        }
    }
?>