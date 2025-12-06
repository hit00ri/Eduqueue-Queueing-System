<?php
include "../db/config.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo "<p>Invalid student id.</p>";
    exit;
}

$stmt = $conn->prepare("SELECT student_id, name, course, year_level, email, created_at FROM students WHERE student_id = ? LIMIT 1");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    http_response_code(404);
    echo "<p>Student not found.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>View Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <?php include './sidebar.php'; ?>

    <div class="main-content">
        <div class="header-row">
            <h3>Student Details</h3>
            <a href="manage_user.php" class="btn btn-secondary">Back to List</a>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Student ID</th>
                        <td><?= htmlspecialchars($student['student_id']) ?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                    </tr>
                    <tr>
                        <th>Course</th>
                        <td><?= htmlspecialchars($student['course']) ?></td>
                    </tr>
                    <tr>
                        <th>Year Level</th>
                        <td><?= htmlspecialchars($student['year_level']) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td><?= htmlspecialchars($student['created_at']) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</body>

</html>