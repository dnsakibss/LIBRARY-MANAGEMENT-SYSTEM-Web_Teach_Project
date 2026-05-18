<?php // app/view/branch_manager/branches.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-building me-2 text-danger"></i>Manage Branches</h2>
  <a href="<?= BASE_URL ?>index.php?page=manager_branch_add" class="btn btn-danger"><i class="bi bi-plus me-1"></i>Add Branch</a>
</div>
<div class="row g-3">
  <?php foreach ($branches as $b): ?>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100 <?= !$b['is_active'] ? 'opacity-50' : '' ?>">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h5 class="fw-bold mb-0"><?= e($b['name']) ?></h5>
          <span class="badge bg-<?= $b['is_active'] ? 'success' : 'secondary' ?>">
            <?= $b['is_active'] ? 'Active' : 'Inactive' ?>
          </span>
        </div>
        <p class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i><?= e($b['city']) ?></p>
        <p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i><?= e($b['phone'] ?? '—') ?></p>
        <p class="text-muted small mb-2"><i class="bi bi-person-badge me-1"></i>Manager: <?= e($b['manager_name'] ?? 'Unassigned') ?></p>
        <p class="text-muted small mb-2"><i class="bi bi-people me-1"></i><?= $b['librarian_count'] ?> librarian(s)</p>
      </div>
      <div class="card-footer bg-white d-flex gap-2">
        <a href="<?= BASE_URL ?>index.php?page=manager_branch_edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-warning flex-fill">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form method="POST" class="flex-fill">
          <input type="hidden" name="branch_id" value="<?= $b['id'] ?>">
          <input type="hidden" name="is_active" value="<?= $b['is_active'] ? 0 : 1 ?>">
          <button class="btn btn-sm btn-outline-<?= $b['is_active'] ? 'secondary' : 'success' ?> w-100"
            onclick="return confirm('<?= $b['is_active'] ? 'Deactivate' : 'Activate' ?> this branch?')">
            <?= $b['is_active'] ? 'Deactivate' : 'Activate' ?>
          </button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
