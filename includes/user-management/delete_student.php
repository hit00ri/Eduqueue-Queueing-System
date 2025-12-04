<?php
    include "../../db/config.php";

    if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
        echo "Error: No user ID provided.";
        exit;
    }

    $id = intval($_GET['student_id']);

    try {
        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() == 0) {
            echo "Error: User does not exist.";
            exit;
        }
        
        // Delete the user
        $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
        $stmt->execute([$id]);
        
        header("Location: ../../staff-management/admin/manage_user.php?message=deleted");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting user: " . htmlspecialchars($e->getMessage());
        exit;
    }
?>
