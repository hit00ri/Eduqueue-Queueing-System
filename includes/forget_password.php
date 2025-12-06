<?php
require_once "../api/forgot_password.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/forgot.css">
</head>
<body class="forgot-body">
<div class="forgot-page">
    <div class="forgot-card">
        <div class="forgot-left">
            <h1 class="forgot-title">Forgot Password</h1>

            <?php if ($message !== ""): ?>
                <div class="forgot-success">
                    <?php echo htmlspecialchars($message); ?>
                    <br><br>
                    <a href="../index.php" class="forgot-link">Click here to log in</a>
                </div>
            <?php endif; ?>

            <?php if ($error !== ""): ?>
                <div class="forgot-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (empty($message)): ?>
            <form class="forgot-form" method="post" action="">
                <label class="forgot-label" for="email">Email</label>
                <input
                    class="forgot-input"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your registered email"
                    value="<?php echo $email_value; ?>"
                    required
                    autofocus
                >
                <p class="password-requirements">Enter the email you used for registration</p>

                <label class="forgot-label" for="new_password">New Password</label>
                <input
                    class="forgot-input"
                    type="password"
                    id="new_password"
                    name="new_password"
                    placeholder="Enter new password (min. 3 characters)"
                    required
                    minlength="3"
                >
                <p class="password-requirements">Password must be at least 3 characters long</p>

                <label class="forgot-label" for="confirm_password">Confirm Password</label>
                <input
                    class="forgot-input"
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm new password"
                    required
                    minlength="3"
                >

                <button class="forgot-submit" type="submit">Reset Password</button>
            </form>
            <?php endif; ?>

            <p class="forgot-footer-text">
                Remember your password?
                <a class="forgot-link" href="../index.php">Back to Log In</a>
            </p>
        </div>

        <div class="forgot-right">
            <div class="forgot-illustration">
                <!-- Logo removed -->
                <h2>Queue Management System</h2>
                <p>Reset your password to regain access to your account and manage queues efficiently.</p>
            </div>
        </div>
    </div>
</div>
<script src="../js/forgot_password.js"></script>
</body>
</html>