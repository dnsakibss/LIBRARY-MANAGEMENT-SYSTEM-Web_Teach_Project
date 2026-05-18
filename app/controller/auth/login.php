<?php
// Person 1 - Core Auth & Member Role
// app/controller/auth/login.php
require_once __DIR__ . '/../../../app/model/UserModel.php';

$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email)    $errors[] = 'Email is required.';
    if (!$password) $errors[] = 'Password is required.';

    if (empty($errors)) {
        $model = new UserModel($conn);
        $user  = $model->findByEmail($email);

        if ($user && $user['is_active'] && password_verify($password, $user['password_hash'])) {
            sessionStart();
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role']      = $user['role'];
            $_SESSION['branch_id'] = $user['branch_id'];
            redirect(dashboardForRole($user['role']));
        } else {
            $errors[] = 'Invalid credentials or account is inactive.';
        }
    }
}

$pageTitle = 'Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — <?= APP_NAME ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

          <!-- Logo & Title -->
          <div class="text-center mb-4">
            <i class="bi bi-book-half fs-1 text-primary"></i>
            <h4 class="fw-bold mt-2"><?= APP_NAME ?></h4>
            <p class="text-muted small">Sign in to your account</p>
          </div>

          <!-- Error Messages -->
          <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger py-2 small">
              <i class="bi bi-exclamation-circle me-1"></i><?= e($err) ?>
            </div>
          <?php endforeach; ?>

          <!-- Login Form -->
          <form method="POST" action="<?= BASE_URL ?>index.php?page=login">
            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" class="form-control"
                     value="<?= e($email) ?>" required autofocus placeholder="you@example.com">
            </div>

            <!-- Password with Show/Hide toggle -->
            <div class="mb-4">
              <label class="form-label fw-semibold">Password</label>
              <div class="input-group">
                <input type="password" name="password" id="passwordInput"
                       class="form-control" required placeholder="Enter password">
                <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                        title="Show/hide password">
                  <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-semibold">
              <i class="bi bi-box-arrow-in-right me-1"></i>Login
            </button>
          </form>

          <hr>
          <p class="text-center small text-muted mb-0">
            New member? <a href="<?= BASE_URL ?>index.php?page=register">Register here</a>
          </p>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Show / Hide password toggle
  document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('toggleIcon');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
  });
</script>
</body>
</html>
