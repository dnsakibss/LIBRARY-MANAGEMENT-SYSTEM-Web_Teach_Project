<?php
$pageTitle = 'Access Denied';
require __DIR__ . '/../../view/shared/header.php';
?>
<div class="text-center py-5">
  <i class="bi bi-lock-fill display-1 text-danger"></i>
  <h2 class="mt-3">Access Denied</h2>
  <p class="text-muted">You do not have permission to access this page.</p>
  <a href="<?= BASE_URL ?>" class="btn btn-primary">Go to Dashboard</a>
</div>
<?php require __DIR__ . '/../../view/shared/footer.php'; ?>
