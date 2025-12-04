<?php
include "../../db/config.php";

$id = intval($_GET['id'] ?? $_GET['user_id'] ?? 0);

if ($id <= 0) {
    echo "Error: Invalid user ID.";
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "Error: User not found.";
        exit;
    }
    // Protect admin accounts from being edited via direct URL
    if (strtolower($user['role'] ?? '') === 'admin') {
        header("Location: ../../staff-management/admin/manage_user.php?error=protected_admin");
        exit;
    }
} catch (PDOException $e) {
    echo "Error fetching user: " . htmlspecialchars($e->getMessage());
    exit;
}

if (isset($_POST['update'])) {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $role = trim($_POST['role'] ?? '');

    try {
        $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, role = ? WHERE user_id = ?");
        $stmt->execute([$name, $username, $role, $id]);
        header("Location: ../../staff-management/admin/manage_user.php?message=updated");
        exit;
    } catch (PDOException $e) {
        echo "Error updating user: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/common.css">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container mt-5">

    <div class="card p-4 shadow-sm">
        <h4>Edit User</h4><hr>

           <!-- The form posts back to this page. The select inputs are
               pre-selected using the $user array fetched above. -->
           <form method="POST">

           <div class="mb-3">
                <label>Name: </label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label>Username: </label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label>Role: </label>
                <select name="role" class="form-control">
                    <option value="cashier" <?= (strtolower($user['role'] ?? '')=="cashier")?"selected":"" ?>>Cashier</option>
                </select>
            </div>


            <button name="update" class="btn btn-primary">Update</button>
            <a href="../../staff-management/admin/manage_user.php" class="btn btn-secondary">Back</a>

        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

</body>
</html>
