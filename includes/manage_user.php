<?php
    // Main listing page for users. Shows a search box and a table with
    // actions to modify or delete a user.
    include "../db/config.php"; // Connect to DB

    // Handle optional search query. We escape the input using
    // `mysqli_real_escape_string` which helps avoid breaking the SQL
    // but using prepared statements is still the safer option.
    $search = "";
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
    }

    // Use prepared statements with PDO - safer than escaping
    $sql = "SELECT * FROM users WHERE user_id LIKE :search OR name LIKE :search";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':search' => "%$search%"]);
    $result = $stmt;

?>
<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>

<body class="bg-light">

    <?php include './sidebar.php'; ?>

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
                            <th>Username</th>
                            <th>User Role</th>
                            <th>Status</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['username'] ?></td>

                                <td>
                                    <span class="badge bg-info"><?= $row['role'] ?></span>
                                </td>

                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>

                                <td>
                                    <a href="user_edit.php?id=<?= $row['user_id'] ?>" class="btn btn-sm btn-outline-primary">
                                        Modify
                                    </a>

                                    <a href="user_delete.php?id=<?= $row['user_id'] ?>"
                                        onclick="return confirm('Delete this user?')" class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</body>

</html>