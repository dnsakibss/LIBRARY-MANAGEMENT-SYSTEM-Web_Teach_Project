<?php // app/view/admin/users.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-people me-2"></i>Manage Users</h2>
  <a href="<?= BASE_URL ?>index.php?page=admin_user_add" class="btn btn-dark"><i class="bi bi-person-plus me-1"></i>Create Staff</a>
</div>
<form method="GET" action="<?= BASE_URL ?>index.php" class="mb-3 d-flex gap-2">
  <input type="hidden" name="page" value="admin_users">
  <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone…" value="<?= e($search) ?>">
  <button class="btn btn-dark">Search</button>
  <?php if ($search): ?><a href="<?= BASE_URL ?>index.php?page=admin_users" class="btn btn-outline-secondary">Clear</a><?php endif; ?>
</form>
<div class="card border-0 shadow-sm"><div class="card-body p-0">
  <div class="table-responsive"><table class="table table-hover align-middle mb-0 small">
    <thead class="table-dark">
      <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Branch</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($users as $i => $u): ?>
    <tr>
      <td class="text-muted"><?= $i + 1 ?></td>
      <td class="fw-semibold"><?= e($u['name']) ?></td>
      <td><?= e($u['email']) ?></td>
      <td>
        <span class="badge bg-<?= ['admin'=>'dark','branch_manager'=>'danger','librarian'=>'success','member'=>'primary'][$u['role']] ?? 'secondary' ?>">
          <?= e(str_replace('_',' ', $u['role'])) ?>
        </span>
      </td>
      <td><?= e($u['branch_name'] ?? '—') ?></td>
      <td><?= $u['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
      <td>
        <div class="d-flex gap-1">
          <a href="<?= BASE_URL ?>index.php?page=admin_user_edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
          <form method="POST" class="d-inline">
            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
            <input type="hidden" name="action" value="<?= $u['is_active'] ? 'deactivate' : 'activate' ?>">
            <button class="btn btn-sm btn-outline-<?= $u['is_active'] ? 'secondary' : 'success' ?>" title="<?= $u['is_active'] ? 'Deactivate' : 'Activate' ?>">
              <i class="bi bi-<?= $u['is_active'] ? 'person-dash' : 'person-check' ?>"></i>
            </button>
          </form>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <div class="card-footer bg-white text-muted small">Showing <?= count($users) ?> user(s)</div>
</div></div>
