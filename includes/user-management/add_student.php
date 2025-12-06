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
        <link rel="stylesheet" href="../../css/common.css">
    </head>

    <body class="bg-light">

        <?php include __DIR__ . '/../../includes/header.php'; ?>

        <div class="container mt-5">
            <div class="card p-4 shadow-sm">
                <h4>Add User</h4>
                <hr>

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
                        <div class="input-group">
                            <select name="year_level" id="yearLevelSelect" class="form-control" required>
                                <option value="" disabled selected>Select Year Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="others">others..</option>
                            </select>
                            <span class="input-group-text" style="padding-right: 12px; padding-left: 12px;">
                                ▼
                            </span>
                        </div>
                        <!-- Text input that appears only when "others..." is selected -->
                        <input type="text" name="other_year" id="otherYearInput" class="form-control mt-2"
                            placeholder="Please specify year level..." style="display: none;">
                    </div>

                    <div class="mb-3">
                        <label>Email: </label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Password: </label>
                        <input type="text" name="password" class="form-control" minlength="3" required>
                    </div>


                    <div class=" d-flex justify-content-end d-grid gap-3">
                        <!-- Submit button: 'save' is used by PHP to detect submission -->
                        <button class="btn btn-success" name="save">Save</button>
                        <a href="../../staff-management/admin/manage_user.php" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.getElementById('yearLevelSelect').addEventListener('change', function () {
                const otherInput = document.getElementById('otherYearInput');

                if (this.value === 'others') {
                    otherInput.style.display = 'block';
                    otherInput.required = true;
                    otherInput.focus();
                } else {
                    otherInput.style.display = 'none';
                    otherInput.required = false;
                    otherInput.value = '';
                }
            });

            // Handle form submission
            document.querySelector('form').addEventListener('submit', function (e) {
                const yearSelect = document.getElementById('yearLevelSelect');
                const otherInput = document.getElementById('otherYearInput');

                if (yearSelect.value === 'others' && !otherInput.value.trim()) {
                    e.preventDefault();
                    alert('Please specify the year level');
                    otherInput.focus();
                }
            });
        </script>
        <?php include __DIR__ . '/../../includes/footer.php'; ?>
    </body>

</html>