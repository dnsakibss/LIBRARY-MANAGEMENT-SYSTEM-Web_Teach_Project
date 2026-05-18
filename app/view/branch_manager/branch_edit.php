<?php // app/view/branch_manager/branch_edit.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Branch</h2>
  <a href="<?= BASE_URL ?>index.php?page=manager_branches" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="row justify-content-center"><div class="col-lg-7">
  <div class="card border-0 shadow-sm"><div class="card-body p-4">
    <?php foreach ($errors as $err): ?><div class="alert alert-danger py-2 small"><?= e($err) ?></div><?php endforeach; ?>
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label fw-semibold">Branch Name *</label>
          <input type="text" name="name" class="form-control" value="<?= e($branch['name']) ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">City</label>
          <input type="text" name="city" class="form-control" value="<?= e($branch['city'] ?? '') ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Address</label>
          <input type="text" name="address" class="form-control" value="<?= e($branch['address'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= e($branch['phone'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Manager</label>
          <select name="manager_id" class="form-select">
            <option value="">-- None --</option>
            <?php foreach ($managers as $m): ?>
            <option value="<?= $m['id'] ?>" <?= $branch['manager_id'] == $m['id'] ? 'selected' : '' ?>><?= e($m['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Status</label>
          <select name="is_active" class="form-select">
            <option value="1" <?= $branch['is_active'] ? 'selected' : '' ?>>Active</option>
            <option value="0" <?= !$branch['is_active'] ? 'selected' : '' ?>>Inactive</option>
          </select>
        </div>
        <div class="col-12 d-flex gap-2">
          <button type="submit" class="btn btn-warning px-4">Update Branch</button>
          <a href="<?= BASE_URL ?>index.php?page=manager_branches" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </div>
    </form>
  </div></div>
</div></div>
