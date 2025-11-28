<?php
require_once __DIR__ . "/../../../db/config.php";

// Check if user is logged in and is cashier
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'cashier') { 
//     header("Location: /eduqueue-main/staff-management/cashier/index.php");
//     exit; 
// }

// ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['call_next'])) {
        $next = $conn->query("SELECT queue_id FROM queue WHERE status='waiting' ORDER BY queue_id ASC LIMIT 1")
                ->fetchColumn();

        if ($next) {
            $stmt = $conn->prepare("UPDATE queue SET status='serving' WHERE queue_id=?");
            $stmt->execute([$next]);
        }
    }

    if (isset($_POST['served'])) {
        $id = intval($_POST['queue_id']);
        $stmt = $conn->prepare("UPDATE queue SET status='served', time_out = NOW() WHERE queue_id=?");
        $stmt->execute([$id]);
    }

    if (isset($_POST['voided'])) {
        $id = intval($_POST['queue_id']);
        $stmt = $conn->prepare("UPDATE queue SET status='voided' WHERE queue_id=?");
        $stmt->execute([$id]);
    }

    header('Location: /eduqueue-queueing-system/staff-management/cashier/dashboard.php');
    exit;
}

// QUERY DATA
// $serving = $conn->query("
//     SELECT q.*, s.name 
//     FROM queue q 
//     JOIN students s ON q.student_id = s.student_id 
//     WHERE q.status = 'serving' 
//     ORDER BY q.queue_id ASC 
//     LIMIT 1
// ")->fetch(PDO::FETCH_ASSOC);
$serving = $conn->query("SELECT q.*, s.name FROM queue q JOIN students s ON q.student_id = s.student_id WHERE q.status = 'serving' ORDER BY q.queue_id ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// $waiting = $conn->query("
//     SELECT q.*, s.name 
//     FROM queue q 
//     JOIN students s ON q.student_id = s.student_id 
//     WHERE q.status = 'waiting' 
//     ORDER BY q.queue_id ASC
// ")->fetchAll(PDO::FETCH_ASSOC);
$waiting = $conn->query("SELECT q.*, s.name FROM queue q JOIN students s ON q.student_id = s.student_id WHERE q.status = 'waiting' ORDER BY q.queue_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>