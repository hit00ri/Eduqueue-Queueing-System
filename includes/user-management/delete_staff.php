<?php
include "../../db/config.php";

if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    echo "Error: No user ID provided.";
    exit;
}

$id = intval($_GET['user_id']);

try {
    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() == 0) {
        echo "Error: User does not exist.";
        exit;
    }
    
    // Delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$id]);
    
    header("Location: ../../staff-management/admin/manage_user.php?message=deleted");
    exit;
} catch (PDOException $e) {
    echo "Error deleting user: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
