<?php // app/view/admin/audit.php ?>
<h2 class="mb-4"><i class="bi bi-clock-history me-2"></i>Audit Log <small class="text-muted fs-6">(Last 100 actions)</small></h2>
<div class="card border-0 shadow-sm"><div class="card-body p-0">
  <div class="table-responsive"><table class="table table-hover align-middle mb-0 small">
    <thead class="table-dark"><tr><th>Type</th><th>Actor</th><th>Subject</th><th>Status</th><th>Time</th></tr></thead>
    <tbody>
    <?php foreach ($auditLog as $entry): ?>
    <tr>
      <td><span class="badge bg-<?= $entry['type']==='borrow'?'primary':'warning text-dark' ?>"><?= ucfirst($entry['type']) ?></span></td>
      <td><?= e($entry['actor']) ?></td>
      <td><?= e($entry['subject']) ?></td>
      <td>
        <?php $sc = ['active'=>'success','returned'=>'secondary','pending'=>'warning','rejected'=>'danger','paid'=>'success','unpaid'=>'danger'][$entry['status']] ?? 'secondary'; ?>
        <span class="badge bg-<?= $sc ?>"><?= ucfirst($entry['status']) ?></span>
      </td>
      <td><?= e(substr($entry['action_time'],0,16)) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($auditLog)): ?>
    <tr><td colspan="5" class="text-muted text-center py-4">No audit entries.</td></tr>
    <?php endif; ?>
    </tbody>
  </table></div>
</div></div>
