<?php
include "../../db/config.php";

if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO students (name, course, year_level, email, password) VALUES (?, ?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $course, $year_level, $email, $password]);
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
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h4>Add User</h4><hr>

           <!-- The form below posts back to this same script. Fields are
               required at the HTML level, but server-side validation is
               still recommended. -->
           <form method="POST">
            <div class="mb-3">
                <label>Name: </label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Course: </label>
                <input type="text" name="course" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Year Level: </label>
                <input type="text" name="year_level" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email: </label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password: </label>
                <input type="text" name="password" class="form-control" minlength="3" required>
            </div>


            <!-- Submit button: 'save' is used by PHP to detect submission -->
            <button class="btn btn-success" name="save">Save</button>
            <a href="../../staff-management/admin/manage_user.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
</body>
</html>
