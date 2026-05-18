<?php // app/view/branch_manager/policies.php ?>
<h2 class="mb-4"><i class="bi bi-gear me-2 text-danger"></i>Branch Policies</h2>
<?php foreach ($branches as $b): $pol = $policies[$b['id']] ?? []; ?>
<div class="card border-0 shadow-sm mb-3">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <span class="fw-semibold"><i class="bi bi-building me-2"></i><?= e($b['name']) ?></span>
    <span class="badge bg-<?= $b['is_active'] ? 'success' : 'secondary' ?>"><?= $b['is_active'] ? 'Active' : 'Inactive' ?></span>
  </div>
  <div class="card-body">
    <form method="POST" class="row g-3 align-items-end">
      <input type="hidden" name="branch_id" value="<?= $b['id'] ?>">
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Max Borrow Days</label>
        <input type="number" name="max_borrow_days" class="form-control form-control-sm"
               value="<?= (int)($pol['max_borrow_days'] ?? DEFAULT_MAX_DAYS) ?>" min="1" max="365" required>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Max Books / Member</label>
        <input type="number" name="max_books_per_member" class="form-control form-control-sm"
               value="<?= (int)($pol['max_books_per_member'] ?? DEFAULT_MAX_BOOKS) ?>" min="1" max="20" required>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Fine Rate / Day (৳)</label>
        <input type="number" name="fine_rate_per_day" class="form-control form-control-sm" step="0.50"
               value="<?= number_format((float)($pol['fine_rate_per_day'] ?? DEFAULT_FINE_RATE), 2) ?>" min="0" required>
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold">Max Renewals</label>
        <input type="number" name="max_renewals" class="form-control form-control-sm"
               value="<?= (int)($pol['max_renewals'] ?? DEFAULT_MAX_RENEWALS) ?>" min="0" max="10" required>
      </div>
      <div class="col-md-1">
        <button class="btn btn-danger btn-sm w-100"><i class="bi bi-save"></i></button>
      </div>
    </form>
  </div>
</div>
<?php endforeach; ?>
