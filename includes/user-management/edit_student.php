<?php
include "../../db/config.php";

$id = intval($_GET['student_id'] ?? $_GET['id'] ?? 0);

if ($id <= 0) {
    echo "Error: Invalid user ID.";
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Error: User not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error fetching user: " . htmlspecialchars($e->getMessage());
    exit;
}

if (isset($_POST['update'])) {
    $name = trim($_POST['name'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $year_level = trim($_POST['year_level'] ?? '');
    $email = trim($_POST['email'] ?? '');

    try {
        $stmt = $conn->prepare("UPDATE students SET name = ?, course = ?, year_level = ?, email = ? WHERE student_id = ?");
        $stmt->execute([$name, $course, $year_level, $email, $id]);
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
            <h4>Edit Student</h4>
            <hr>

            <!-- The form posts back to this page. The select inputs are
               pre-selected using the $user array fetched above. -->
            <form method="POST">

                <div class="mb-3">
                    <label>Name: </label>
                    <input type="text" name="name" class="form-control" value="<?= $user['name'] ?>" required>
                </div>

                <div class="mb-3">
                    <label>Course</label>
                    <input type="text" name="course" class="form-control" value="<?= $user['course'] ?>" required>
                </div>

                <div class="mb-3">
                    <label>Year Level: </label>
                    <input type="text" name="year_level" class="form-control" value="<?= $user['year_level'] ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label>Email: </label>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>

                <div class=" d-flex justify-content-end d-grid gap-3">
                    <button name="update" class="btn btn-primary">Update</button>
                    <a href="../../staff-management/admin/manage_user.php" class="btn btn-secondary">Back</a>
                </div>

            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

</body>

</html>