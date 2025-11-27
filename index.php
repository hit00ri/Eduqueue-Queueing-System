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
<button class="dark-toggle" title="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>
<div class="login-container card fade-in">
  <div class="login-logo">
    <i class="bi bi-ticket-perforated" style="font-size:28px;color:var(--accent)"></i>
    <h1>balbablbalbalal</h1>
    <h1>Eduqueue</h1>
  </div>
  <?php if ($err): ?>
    <div class="alert alert-danger"><?=htmlspecialchars($err)?></div>
  <?php endif; ?>

  <form method="post" class="mb-3">
      <!-- Username/Student ID -->
      <div class="mb-3">
          <label class="form-label">bggfgfgfgfgfg</label>
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
          <span class="material-symbols-outlined" style="vertical-align:middle">login</span>
          Log In
      </button>
  </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/darkmode.js"></script>
</body>
</html>
