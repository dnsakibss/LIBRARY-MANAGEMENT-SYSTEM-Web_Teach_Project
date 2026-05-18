<?php // app/view/branch_manager/librarians.php ?>
<h2 class="mb-4"><i class="bi bi-person-badge me-2 text-danger"></i>Librarian Assignments</h2>
<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-danger">
          <tr><th>Librarian</th><th>Email</th><th>Current Branch</th><th>Re-assign</th></tr>
        </thead>
        <tbody>
        <?php foreach ($librarians as $lib): ?>
        <tr>
          <td class="fw-semibold"><?= e($lib['name']) ?></td>
          <td><?= e($lib['email']) ?></td>
          <td><?= e($lib['branch_name'] ?? 'Unassigned') ?></td>
          <td>
            <form method="POST" class="d-inline-flex gap-2 align-items-center">
              <input type="hidden" name="user_id" value="<?= $lib['id'] ?>">
              <select name="branch_id" class="form-select form-select-sm" style="width:auto">
                <option value="">-- Select Branch --</option>
                <?php foreach ($branches as $b): ?>
                <option value="<?= $b['id'] ?>" <?= $lib['branch_id'] == $b['id'] ? 'selected' : '' ?>>
                  <?= e($b['name']) ?>
                </option>
                <?php endforeach; ?>
              </select>
              <button class="btn btn-sm btn-danger">Assign</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($librarians)): ?>
        <tr><td colspan="4" class="text-muted text-center py-4">No librarians found.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
