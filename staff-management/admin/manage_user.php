<?php
    // Main listing page for users. Shows a search box and a table with
    // actions to modify or delete a user.
    include "../../db/config.php"; // Connect to DB

    // Handle optional search query. We escape the input using
    // `mysqli_real_escape_string` which helps avoid breaking the SQL
    // but using prepared statements is still the safer option.
    $search = "";
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
    }

    // Fetch both users and students in one result set with consistent columns
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
             'student' AS type,
             course,
             year_level
        FROM students
        WHERE name LIKE :search OR email LIKE :search

        ORDER BY name ASC
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([':search' => "%$search%"]);
        $result = $stmt;
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        $result = [];
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
                <input type="text" name="search" placeholder="Search User" value="<?= $search ?>">
                <button class="btn btn-secondary">Search</button>
            </form>

            <a href="user_add.php" class="btn btn-warning">Add User</a>
        </div>


        <div class="card shadow-sm">
            <div class="card-body">

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                                <th>Name</th>
                                <th>Username / Email</th>
                                <th>Course</th>
                                <th>Year Level</th>
                                <th>Role</th>
                                <th width="200">Actions</th>
                            </tr>
                    </thead>

                    <tbody>
                        <?php if (is_array($result) || $result instanceof PDOStatement): ?>
                            <?php while ($row = is_array($result) ? array_shift($result) : $result->fetch(PDO::FETCH_ASSOC)): ?>
                                <?php if (!$row) break; ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= isset($row['course']) && $row['course'] !== null ? htmlspecialchars($row['course']) : '-' ?></td>
                                    <td><?= isset($row['year_level']) && $row['year_level'] !== null ? htmlspecialchars($row['year_level']) : '-' ?></td>

                                    <td>
                                        <span class="badge bg-info"><?= htmlspecialchars(ucfirst($row['role'])) ?></span>
                                    </td>


                                    <td>
                                        <?php if ($row['type'] === 'user'): ?>
                                            <a href="/Eduqueue-Queueing-System/staff-management/admin/user_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Modify</a>
                                            <a href="/Eduqueue-Queueing-System/staff-management/admin/user_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">Delete</a>
                                        <?php else: ?>
                                            <a href="/Eduqueue-Queueing-System/includes/student_view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="/Eduqueue-Queueing-System/staff-management/admin/student_delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this student?')">Delete</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</body>

</html>