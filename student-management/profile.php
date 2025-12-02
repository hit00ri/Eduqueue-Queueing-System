<?php
// profile.php
require_once "../api/student-api/profile-b.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile - Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <?php include '../includes/student_sidebar.php'; ?>

    <div class="container mt-4" >
        <div class="profile-container card fade-in" style="margin-top: 100px; margin-bottom: 50px">
            <!-- Header -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h1 class="h3">My Profile</h1>
                <p class="text-muted">Manage your account information</p>
            </div>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Profile Form -->
            <form method="post">
                <!-- Student Information -->
                <div class="form-section">
                    <h5 class="mb-3"><i class="bi bi-person-badge"></i> Student Information</h5>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Student ID:</strong></label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($studentData['student_id']) ?>" readonly>
                        <small class="form-text text-muted">Student ID cannot be changed.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Full Name:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined">person</span>
                            </span>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($studentData['name']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Course:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined">school</span>
                            </span>
                            <select name="course" class="form-control" required>
                                <option value="">Select Course</option>
                                <option value="BSIT" <?= ($studentData['course'] ?? '') === 'BSIT' ? 'selected' : '' ?>>BSIT - Bachelor of Science in Information Technology</option>
                                <option value="BSCS" <?= ($studentData['course'] ?? '') === 'BSCS' ? 'selected' : '' ?>>BSCS - Bachelor of Science in Computer Science</option>
                                <option value="BSIS" <?= ($studentData['course'] ?? '') === 'BSIS' ? 'selected' : '' ?>>BSIS - Bachelor of Science in Information Systems</option>
                                <option value="BSCE" <?= ($studentData['course'] ?? '') === 'BSCE' ? 'selected' : '' ?>>BSCE - Bachelor of Science in Computer Engineering</option>
                                <option value="BSBA" <?= ($studentData['course'] ?? '') === 'BSBA' ? 'selected' : '' ?>>BSBA - Bachelor of Science in Business Administration</option>
                                <option value="BSEE" <?= ($studentData['course'] ?? '') === 'BSEE' ? 'selected' : '' ?>>BSEE - Bachelor of Science in Electrical Engineering</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Year Level:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined">calendar_clock</span>
                            </span>
                            <select name="year_level" class="form-control" required>
                                <option value="">Select Year Level</option>
                                <option value="1st Year" <?= ($studentData['year_level'] ?? '') === '1st Year' ? 'selected' : '' ?>>1st Year</option>
                                <option value="2nd Year" <?= ($studentData['year_level'] ?? '') === '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                                <option value="3rd Year" <?= ($studentData['year_level'] ?? '') === '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                                <option value="4th Year" <?= ($studentData['year_level'] ?? '') === '4th Year' ? 'selected' : '' ?>>4th Year</option>
                                <option value="5th Year" <?= ($studentData['year_level'] ?? '') === '5th Year' ? 'selected' : '' ?>>5th Year</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Email:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined">email</span>
                            </span>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($studentData['email']) ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="password-section">
                    <h5 class="mb-3"><i class="bi bi-shield-lock"></i> Change Password</h5>
                    <small class="text-muted d-block mb-3">Leave blank to keep current password</small>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Current Password:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined">lock</span>
                            </span>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>New Password:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined">lock_reset</span>
                            </span>
                            <input type="password" name="new_password" class="form-control" minlength="3">
                        </div>
                        <small class="form-text text-muted">Password must be at least 3 characters long.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Confirm New Password:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined">lock_reset</span>
                            </span>
                            <input type="password" name="confirm_password" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" name="update_profile" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i> Update Profile
                    </button>
                    <a href="student_dashboard.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <?php include "../includes/footer.php"; ?>
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/darkmode.js"></script>
</body>
</html>