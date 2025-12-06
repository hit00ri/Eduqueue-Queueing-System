<?php
require_once "../api/student-api/register-b.php";
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Student Registration - Queuing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <style>
        .registration-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <!-- Dark Mode Toggle -->
    <button class="dark-toggle btn btn-outline-secondary position-fixed top-0 end-0 m-3">
        <i class="bi bi-moon-stars"></i>
    </button>

    <div class="registration-container card fade-in">
        <div class="login-logo text-center mb-4">
            <i class="bi bi-person-plus" style="font-size:28px;color:var(--accent)"></i>
            <h1>Student Registration</h1>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" class="mb-3">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="material-symbols-outlined">person</span>
                    </span>
                    <input type="text" name="name" class="form-control"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Course</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="material-symbols-outlined">school</span>
                    </span>
                    <select name="course" class="form-control" required>
                        <option value="">Select Course</option>
                        <option value="BSIT" <?= ($_POST['course'] ?? '') === 'BSIT' ? 'selected' : '' ?>>BSIT - Bachelor
                            of Science in Information Technology</option>
                        <option value="BSCS" <?= ($_POST['course'] ?? '') === 'BSCS' ? 'selected' : '' ?>>BSCS - Bachelor
                            of Science in Computer Science</option>
                        <option value="BSIS" <?= ($_POST['course'] ?? '') === 'BSIS' ? 'selected' : '' ?>>BSIS - Bachelor
                            of Science in Information Systems</option>
                        <option value="BSCE" <?= ($_POST['course'] ?? '') === 'BSCE' ? 'selected' : '' ?>>BSCE - Bachelor
                            of Science in Computer Engineering</option>
                        <option value="BSBA" <?= ($_POST['course'] ?? '') === 'BSBA' ? 'selected' : '' ?>>BSBA - Bachelor
                            of Science in Business Administration</option>
                        <option value="BSEE" <?= ($_POST['course'] ?? '') === 'BSEE' ? 'selected' : '' ?>>BSEE - Bachelor
                            of Science in Electrical Engineering</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Year Level</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="material-symbols-outlined">calendar_clock</span>
                    </span>
                    <select name="year_level" class="form-control" required>
                        <option value="">Select Year Level</option>
                        <option value="1st Year" <?= ($_POST['year_level'] ?? '') === '1st Year' ? 'selected' : '' ?>>1st
                            Year</option>
                        <option value="2nd Year" <?= ($_POST['year_level'] ?? '') === '2nd Year' ? 'selected' : '' ?>>2nd
                            Year</option>
                        <option value="3rd Year" <?= ($_POST['year_level'] ?? '') === '3rd Year' ? 'selected' : '' ?>>3rd
                            Year</option>
                        <option value="4th Year" <?= ($_POST['year_level'] ?? '') === '4th Year' ? 'selected' : '' ?>>4th
                            Year</option>
                        <option value="5th Year" <?= ($_POST['year_level'] ?? '') === '5th Year' ? 'selected' : '' ?>>5th
                            Year</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="material-symbols-outlined">email</span>
                    </span>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="material-symbols-outlined">lock</span>
                    </span>
                    <input type="password" name="password" class="form-control" required minlength="3">
                </div>
                <small class="form-text text-muted">Password must be at least 3 characters long.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="material-symbols-outlined">lock_reset</span>
                    </span>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">
                <span class="material-symbols-outlined" style="vertical-align:middle">person_add</span>
                Register
            </button>
        </form>

        <div class="text-center">
            <p>Already have an account?
                <a href="student_login.php" class="btn btn-outline-secondary btn-sm">
                    <span class="material-symbols-outlined" style="vertical-align:middle">login</span>
                    Login here
                </a>
            </p>
        </div>
    </div>

    <script src="../js/darkmode.js"></script>
</body>

</html>