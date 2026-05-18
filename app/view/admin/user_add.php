<?php // app/view/admin/user_add.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-person-plus me-2"></i>Create Staff Account</h2>
  <a href="<?= BASE_URL ?>index.php?page=admin_users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-7">
  <div class="card border-0 shadow-sm"><div class="card-body p-4">
    <?php foreach ($errors as $err): ?><div class="alert alert-danger py-2 small"><?= e($err) ?></div><?php endforeach; ?>
    <form method="POST">
      <div class="row g-3">
        <div class="col-12"><label class="form-label fw-semibold">Full Name *</label>
          <input type="text" name="name" class="form-control" value="<?= e($old['name']??'') ?>" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Email *</label>
          <input type="email" name="email" class="form-control" value="<?= e($old['email']??'') ?>" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= e($old['phone']??'') ?>"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Role *</label>
          <select name="role" class="form-select" required>
            <option value="librarian"      <?= ($old['role']??'')==='librarian'?'selected':'' ?>>Librarian</option>
            <option value="branch_manager" <?= ($old['role']??'')==='branch_manager'?'selected':'' ?>>Branch Manager</option>
            <option value="admin"          <?= ($old['role']??'')==='admin'?'selected':'' ?>>Admin</option>
          </select></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Branch</label>
          <select name="branch_id" class="form-select">
            <option value="">-- None --</option>
            <?php foreach ($branches as $b): ?>
            <option value="<?= $b['id'] ?>" <?= ($old['branch_id']??'')==$b['id']?'selected':'' ?>><?= e($b['name']) ?></option>
            <?php endforeach; ?>
          </select></div>
        <div class="col-12"><label class="form-label fw-semibold">Password * (min 6 chars)</label>
          <input type="password" name="password" class="form-control" minlength="6" required></div>
        <div class="col-12 d-flex gap-2">
          <button type="submit" class="btn btn-dark px-4"><i class="bi bi-check me-1"></i>Create Account</button>
          <a href="<?= BASE_URL ?>index.php?page=admin_users" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </div>
    </form>
  </div></div>
</div></div>
