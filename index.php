<?php
// TEMP DEBUG: show errors in browser while troubleshooting. Remove in production.
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . "/api/staff-api/index-b.php";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Queuing System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    
<header class="header">
    <div class="header-content">
        <div class="header-logo">
            <img src="./img/SLC LOGO.png" alt="slc logo" />
        </div>
        <div class="header-text">
            <p class= "header-title">Saint Louis College</p>
            <p class="header-subtitle">City of San Fernando, La Union</p>
            <p class="header-system">Queuing Management System with Payment Tracking</p>
        </div>
    </div>
</header>

<button class="dark-toggle" title="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>
<div class="login-container">
  <div class="login-logo">
    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 32 32"><path fill="#003d7a" d="M16.002 11.5c-.764 0-1.48-.201-2.1-.554c.84-1 1.346-2.289 1.346-3.696a5.73 5.73 0 0 0-1.346-3.696a4.25 4.25 0 1 1 2.1 7.946m1.5 4c0-.946-.329-1.815-.877-2.5h3.377a2.5 2.5 0 0 1 2.5 2.5v7a6.5 6.5 0 0 1-8.078 6.307a7.99 7.99 0 0 0 3.078-6.307zm5-4c-.764 0-1.48-.201-2.1-.554c.84-1 1.345-2.289 1.345-3.696a5.73 5.73 0 0 0-1.345-3.696a4.25 4.25 0 1 1 2.1 7.946m1.5 4c0-.946-.329-1.815-.877-2.5h3.377a2.5 2.5 0 0 1 2.5 2.5v7a6.5 6.5 0 0 1-8.078 6.307a7.99 7.99 0 0 0 3.078-6.307zM5.5 13A2.5 2.5 0 0 0 3 15.5v7a6.5 6.5 0 1 0 13 0v-7a2.5 2.5 0 0 0-2.5-2.5zm4-1.5a4.25 4.25 0 1 0 0-8.5a4.25 4.25 0 0 0 0 8.5"/></svg>
    <h1>EDUQUEUE</h1>
    <h6>Account Login</h6>
  </div>
  <?php if ($err): ?>
    <div class="alert alert-danger"><?=htmlspecialchars($err)?></div>
  <?php endif; ?>

  <form method="post" class="mb-3">
      <!-- Username/Student ID -->
      <div class="mb-3">
          <label class="form-label">ID Number</label>
          <div class="input-group">
              <span class="input-group-text">
                  <span class="material-symbols-outlined">person</span>
              </span>
              <input name="username" class="form-control" required>
          </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
              <span class="input-group-text">
                  <span class="material-symbols-outlined">lock</span>
              </span>
              <input name="password" type="password" class="form-control" required>
          </div>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">
          Log In
      </button>
      <p class="forgot-password"><em>Forgot Password?</em></p>
  </form>

</div>

<footer class="footer">
  <p>&copy; 2025 Saint Louis College of San Fernando, La Union. All rights reserved.</p>
</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/darkmode.js"></script>
</body>
</html>
