<?php
// app/controller/auth/register.php
//this is the register.php file in the auth controller, it will handle the registration of new members, validate the input, and save the new user to the database
require_once __DIR__ . '/../../../app/model/UserModel.php';
require_once __DIR__ . '/../../../app/model/Models.php';

$errors = [];
$old    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST;
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $branchId = (int)($_POST['branch_id'] ?? 0);

    if (!$name)       $errors[] = 'Full name is required.';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (!$phone)      $errors[] = 'Phone number is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';
    if (!$branchId)   $errors[] = 'Please select a branch.';

    if (empty($errors)) {
        $userModel = new UserModel($conn);
        if ($userModel->emailExists($email)) {
            $errors[] = 'This email is already registered.';
        } else {
            if ($userModel->register($name, $email, $phone, $password, $branchId)) {
                setFlash('success', 'Registration successful! Please login.');
                redirect('index.php?page=login');
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
}

$branchModel = new BranchModel($conn);
$branches    = $branchModel->getAll();

$pageTitle = 'Register';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Register — <?= APP_NAME ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body class="bg-light d-flex align-items-center min-vh-100 py-4">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <i class="bi bi-person-plus fs-1 text-primary"></i>
            <h4 class="fw-bold mt-2">Create Member Account</h4>
          </div>

          <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger py-2 small"><?= e($err) ?></div>
          <?php endforeach; ?>

          <form method="POST" action="<?= BASE_URL ?>index.php?page=register">
            <div class="mb-3">
              <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" value="<?= e($old['name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" value="<?= e($old['email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
              <input type="text" name="phone" class="form-control" value="<?= e($old['phone'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Primary Branch <span class="text-danger">*</span></label>
              <select name="branch_id" class="form-select" required>
                <option value="">-- Select Branch --</option>
                <?php foreach ($branches as $b): if (!$b['is_active']) continue; ?>
                  <option value="<?= $b['id'] ?>" <?= ($old['branch_id'] ?? 0) == $b['id'] ? 'selected' : '' ?>>
                    <?= e($b['name']) ?> — <?= e($b['city']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" minlength="6" required>
              <div class="form-text">At least 6 characters</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
              <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold">
              <i class="bi bi-person-check me-1"></i>Register
            </button>
          </form>
          <hr>
          <p class="text-center small mb-0">Already have an account? <a href="<?= BASE_URL ?>index.php?page=login">Login</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
