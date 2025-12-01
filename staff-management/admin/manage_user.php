<?php
    // Main listing page for users. Shows a search box and a table with
    // actions to modify or delete a user.
    include "../../db/config.php"; // Connect to DB

    // Handle optional search query.
    $search = "";
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
    }

    // Fetch only users (admin/cashier) for the first table
    $sql_users = "
        SELECT user_id AS id,
               name,
               username,
               role,
               'user' AS type
        FROM users
        WHERE name LIKE :search OR username LIKE :search
        ORDER BY name ASC
    ";

    try {
        $stmt = $conn->prepare($sql_users);
        $stmt->execute([':search' => "%$search%"]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        $users = [];
    }

    // Also fetch students-only list for a dedicated table
    try {
        $sqlStudents = "SELECT student_id AS id, name, course, year_level, email FROM students WHERE name LIKE :search OR email LIKE :search ORDER BY name ASC";
        $stmt2 = $conn->prepare($sqlStudents);
        $stmt2->execute([':search' => "%$search%"]);
        $studentsOnly = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $studentsOnly = [];
    }

?>
<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Eduqueue-Queueing-System/css/common.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>

<body class="bg-light">

    <?php include '../../includes/sidebar.php'; ?>

    <div class="main-content">

        <div class="header-row">
            <h3>User Management</h3>

            <form method="GET" style="display:flex; gap:10px;">
                <input type="text" name="search" placeholder="Search User" value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-secondary">Search</button>
            </form>
            <br>
            <a href="../../includes/user-management/add-staff.php" class="btn btn-warning">Add Staff</a>
            <a href="../../includes/user-management/add_student.php" class="btn btn-warning">Add Student</a>
           
        </div>

        <br>
        
        <!-- ===================== USERS TABLE (Admin/Cashier Only) ===================== -->
        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="mb-3">System Users (Admin/Cashier)</h5>

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No users found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'info' ?>">
                                            <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (strtolower($user['role'] ?? '') === 'admin'): ?>
                                            <button class="btn btn-sm btn-outline-secondary" disabled>Modify</button>
                                            <button class="btn btn-sm btn-outline-danger" disabled style="pointer-events:none;">Delete</button>
                                        <?php else: ?>
                                            <a href="../../includes/user-management/edit_staff.php?user_id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">Modify</a>
                                            <a href="../../includes/user-management/delete_staff.php?user_id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">Delete</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
        
        <!-- ===================== STUDENTS-ONLY TABLE ===================== -->
        <div class="card shadow-sm mt-4">
            <div class="card-body">

                <h5 class="mb-3">Student Accounts</h5>

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Email</th>
                            <th>Year Level</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($studentsOnly)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No students found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($studentsOnly as $stu): ?>
                            <tr>
                                <td><?= htmlspecialchars($stu['name']) ?></td>
                                <td><?= htmlspecialchars($stu['course']) ?></td>
                                <td><?= htmlspecialchars($stu['email']) ?></td>
                                <td><?= htmlspecialchars($stu['year_level']) ?></td>
                                <td>
                                    <a href="../../includes/user-management/edit_student.php?student_id=<?= $stu['id'] ?>" class="btn btn-sm btn-outline-primary">Modify</a>
                                    <a href="../../includes/user-management/delete_student.php?student_id=<?= $stu['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this student?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script>
        let searchInput = document.querySelector("input[name='search']");
        let timer = null;

        searchInput.addEventListener("keyup", function () {
            clearTimeout(timer); // Reset timer every keypress

            timer = setTimeout(() => {
                this.form.submit(); // Submit ONLY after user stops typing
            }, 500); // 500ms = half second
        });
    </script>



</body>

</html>