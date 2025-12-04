<?php
include "../../db/config.php";

if (isset($_POST['save'])) {
    $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Use PDO prepared statement to safely insert user
        $sql = "INSERT INTO users (name, username, password, role) 
            VALUES (?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $username, $password, $role]);
        
        // After adding the user redirect back to the listing page.
        header("Location: ../../staff-management/admin/manage_user.php");
        exit;
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Error adding user: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add User</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/common.css">
    </head>
    <body class="bg-light">

    <?php include __DIR__ . '/../../includes/header.php'; ?>

        <div class="container mt-5">
            <div class="card p-4 shadow-sm">
                <h4>Add User</h4><hr>

                <form method="POST">
                    <div class="mb-3">
                        <label>Name: </label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Username: </label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Password: </label>
                        <input type="text" name="password" class="form-control" minlength="3" required>
                    </div>

                    <div class="mb-3">
                        <label>User Role</label>
                            <select name="role" class="form-control">
                                <option>Cashier</option>
                            </select>
                    </div>

                    <button class="btn btn-success" name="save">Save</button>
                    <a href="../../staff-management/admin/manage_user.php" class="btn btn-secondary">Back</a>
                </form>
                
            </div>
        </div>
        <?php include __DIR__ . '/../../includes/footer.php'; ?>
    </body>
</html>
