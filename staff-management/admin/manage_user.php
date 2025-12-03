<?php
    require_once __DIR__ . '/../../db/config.php';
    require_once __DIR__ . '/../../api/auth.php';
    if (!function_exists('require_role')) {
        function require_role($role) {
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
                header('Location: /Eduqueue-Queueing-System/login.php');
                exit;
            }
        }
    }
    require_role('admin');

    $search = "";
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
    }

    // Fetch users (admin/cashier)
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

    // Fetch students
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Eduqueue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Eduqueue-Queueing-System/css/common.css">
    <link rel="stylesheet" href="/Eduqueue-Queueing-System/css/user_management.css">
    <link rel="stylesheet" href="../../css/manage_user.css">
</head>
<body>
    <?php include __DIR__ . '../../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="user-management-container">
        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-number"><?= count($users) ?></div>
                    <div class="stats-label">Total Staff</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-number"><?= count($studentsOnly) ?></div>
                    <div class="stats-label">Total Students</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-number"><?= count($users) + count($studentsOnly) ?></div>
                    <div class="stats-label">Total Users</div>
                </div>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="header-actions d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-0 fw-bold text-primary">User Management</h3>
                <p class="text-muted mb-0">Manage staff and student accounts</p>
            </div>
            
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="search-container">
                    <i class="bi bi-search"></i>
                    <form method="GET" class="mb-0">
                        <input type="text" name="search" class="form-control" placeholder="Search users..." 
                               value="<?= htmlspecialchars($search) ?>">
                    </form>
                </div>
                
                <div class="d-flex gap-2">
                    <a href="../../includes/user-management/add-staff.php" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-person-plus"></i> Add Staff
                    </a>
                    <a href="../../includes/user-management/add_student.php" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="bi bi-person-add"></i> Add Student
                    </a>
                </div>
            </div>
        </div>

        <!-- Staff Users Table -->
        <div class="card card-custom">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>System Staff (Admin/Cashier)
                </h5>
                <span class="badge bg-light text-dark"><?= count($users) ?> users</span>
            </div>
            <div class="card-body p-0">
                <div class="table-scroll-container">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-people"></i>
                                            <h5>No staff found</h5>
                                            <p>Add new staff members to get started</p>
                                            <a href="../../includes/user-management/add-staff.php" class="btn btn-primary btn-sm">
                                                <i class="bi bi-person-plus"></i> Add Staff
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3 bg-light text-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <div>
                                                    <strong><?= htmlspecialchars($user['name']) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td>
                                            <span class="badge <?= $user['role'] === 'admin' ? 'badge-admin' : 'badge-cashier' ?> text-white">
                                                <i class="bi bi-<?= $user['role'] === 'admin' ? 'shield-check' : 'cash' ?> me-1"></i>
                                                <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-btn-group">
                                                <?php if (strtolower($user['role'] ?? '') === 'admin'): ?>
                                                    <button class="btn btn-outline-secondary btn-action" disabled>
                                                        <i class="bi bi-pencil"></i> Modify
                                                    </button>
                                                    <button class="btn btn-outline-secondary btn-action" disabled>
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                <?php else: ?>
                                                    <a href="../../includes/user-management/edit_staff.php?user_id=<?= $user['id'] ?>" 
                                                       class="btn btn-outline-primary btn-action">
                                                        <i class="bi bi-pencil"></i> Modify
                                                    </a>
                                                    <a href="../../includes/user-management/delete_staff.php?user_id=<?= $user['id'] ?>" 
                                                       class="btn btn-outline-danger btn-action"
                                                       onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card card-custom">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-mortarboard-fill me-2"></i>Student Accounts
                </h5>
                <span class="badge bg-light text-dark"><?= count($studentsOnly) ?> students</span>
            </div>
            <div class="card-body p-0">
                <div class="table-scroll-container">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Email</th>
                                <th>Year Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($studentsOnly)): ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="bi bi-mortarboard"></i>
                                            <h5>No students found</h5>
                                            <p>Add new students to get started</p>
                                            <a href="../../includes/user-management/add_student.php" class="btn btn-success btn-sm">
                                                <i class="bi bi-person-add"></i> Add Student
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($studentsOnly as $stu): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3 bg-light text-success d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <div>
                                                    <strong><?= htmlspecialchars($stu['name']) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-3 py-1">
                                                <?= htmlspecialchars($stu['course']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="mailto:<?= htmlspecialchars($stu['email']) ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($stu['email']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-white px-3 py-2">
                                                Year <?= htmlspecialchars($stu['year_level']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-btn-group">
                                                <a href="../../includes/user-management/edit_student.php?student_id=<?= $stu['id'] ?>" 
                                                   class="btn btn-outline-primary btn-action">
                                                    <i class="bi bi-pencil"></i> Modify
                                                </a>
                                                <a href="../../includes/user-management/delete_student.php?student_id=<?= $stu['id'] ?>" 
                                                   class="btn btn-outline-danger btn-action"
                                                   onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-submit search on typing
        let searchInput = document.querySelector("input[name='search']");
        let timer = null;

        if (searchInput) {
            searchInput.addEventListener("keyup", function () {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        }
    </script>

    <?php include __DIR__ . '../../../includes/footer.php'; ?>
</body>
</html>