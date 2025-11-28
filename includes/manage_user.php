<?php
include "../db/config.php";

$search = isset($_GET['search']) ? $_GET['search'] : "";

// FETCH USERS + STUDENTS using UNION for consistent columns
$sql = "
    SELECT user_id AS id,
           name,
           username,
           role,
           'user' AS type,
           NULL AS course,
           NULL AS year_level
    FROM users
    WHERE name LIKE :search OR username LIKE :search

    UNION ALL

    SELECT student_id AS id,
           name,
           email AS username,
           'student' AS role,
           course,
           year_level
    FROM students
    WHERE name LIKE :search OR email LIKE :search

    ORDER BY name ASC
";

$rows = [];
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute([':search' => "%$search%"]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Fetch students for second table
$sql_students = "
    SELECT student_id AS id,
           name,
           course,
           year_level,
           email
    FROM students
    WHERE name LIKE :search OR email LIKE :search
    ORDER BY name ASC
";

$stmt2 = $conn->prepare($sql_students);
$stmt2->execute([':search' => "%$search%"]);
$studentsOnly = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<?php include './sidebar.php'; ?>

<div class="main-content">

    <div class="header-row mb-3">
        <h3>User & Student Management</h3>

        <form method="GET" style="display:flex; gap:10px;">
            <input type="text" name="search" placeholder="Search..." value="<?= $search ?>">
            <button class="btn btn-secondary">Search</button>
        </form>
    </div>

    <!-- ===================== MAIN TABLE (USERS + STUDENTS) ===================== -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="mb-3">All Accounts (Users + Students)</h5>

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Username / Email</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Role</th>
                        <th>Type</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No records found.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= $row['type'] === 'student' ? htmlspecialchars($row['course']) : '-' ?></td>
                            <td><?= $row['type'] === 'student' ? htmlspecialchars($row['year_level']) : '-' ?></td>

                            <td><span class="badge bg-info"><?= ucfirst($row['role']) ?></span></td>

                            <td><span class="badge bg-dark"><?= strtoupper($row['type']) ?></span></td>

                            <td>
                                <?php if ($row['type'] === 'user'): ?>
                                    <a href="user_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="user_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Delete this user?')">Delete</a>

                                <?php else: ?>
                                    <a href="student_view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="student_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Delete this student?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>


    <!-- ===================== STUDENTS-ONLY TABLE ===================== -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">Student Accounts Only</h5>

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Section</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($studentsOnly)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No students found.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($studentsOnly as $stu): ?>
                    <tr>
                        <td><?= htmlspecialchars($stu['name']) ?></td>

                        <!-- Section = course + year level -->
                        <td><?= htmlspecialchars($stu['course'] . " - " . $stu['year_level']) ?></td>

                        <td><?= htmlspecialchars($stu['email']) ?></td>
                        <td><?= htmlspecialchars($stu['course']) ?></td>
                        <td><?= htmlspecialchars($stu['year_level']) ?></td>

                        <td>
                            <a href="student_view.php?id=<?= $stu['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="student_delete.php?id=<?= $stu['id'] ?>" 
                               onclick="return confirm('Delete this student?')"
                               class="btn btn-sm btn-outline-danger">
                               Delete
                           </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>
