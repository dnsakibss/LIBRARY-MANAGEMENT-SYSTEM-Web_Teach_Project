<?php // app/view/admin/branches.php ?>
<h2 class="mb-4"><i class="bi bi-building me-2"></i>All Branches</h2>
<div class="card border-0 shadow-sm"><div class="card-body p-0">
  <div class="table-responsive"><table class="table table-hover align-middle mb-0">
    <thead class="table-dark"><tr><th>Name</th><th>City</th><th>Phone</th><th>Manager</th><th>Librarians</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($branches as $b): ?>
    <tr>
      <td class="fw-semibold"><?= e($b['name']) ?></td>
      <td><?= e($b['city']??'—') ?></td>
      <td><?= e($b['phone']??'—') ?></td>
      <td><?= e($b['manager_name']??'Unassigned') ?></td>
      <td><span class="badge bg-info text-dark"><?= $b['librarian_count'] ?></span></td>
      <td><?= $b['is_active']?'<span class="badge bg-success">Active</span>':'<span class="badge bg-secondary">Inactive</span>' ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
</div></div>
