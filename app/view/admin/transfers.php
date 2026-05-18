<?php // app/view/admin/transfers.php ?>
<h2 class="mb-4"><i class="bi bi-arrow-left-right me-2"></i>All Inter-Branch Transfer Requests</h2>
<div class="card border-0 shadow-sm"><div class="card-body p-0">
  <div class="table-responsive"><table class="table table-hover align-middle mb-0 small">
    <thead class="table-dark"><tr><th>Book</th><th>From</th><th>To</th><th>Requested By</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($transfers as $t):
      $bmap = ['pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'secondary'];
      $cls  = $bmap[$t['status']] ?? 'secondary';
    ?>
    <tr>
      <td><?= e($t['book_title']) ?></td>
      <td><?= e($t['from_branch']) ?></td>
      <td><?= e($t['to_branch']) ?></td>
      <td><?= e($t['requested_by_name']) ?></td>
      <td><?= e(substr($t['created_at'],0,10)) ?></td>
      <td><span class="badge bg-<?= $cls ?> text-<?= $cls==='warning'?'dark':'white' ?>"><?= ucfirst($t['status']) ?></span></td>
      <td>
        <?php if ($t['status']==='pending'): ?>
        <form method="POST" class="d-inline-flex gap-1">
          <input type="hidden" name="transfer_id" value="<?= $t['id'] ?>">
          <button name="status" value="approved" class="btn btn-xs btn-sm btn-success">Approve</button>
          <button name="status" value="rejected" class="btn btn-xs btn-sm btn-danger">Reject</button>
        </form>
        <?php else: ?><span class="text-muted">—</span><?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if(empty($transfers)): ?><tr><td colspan="7" class="text-muted text-center py-4">No requests.</td></tr><?php endif; ?>
    </tbody>
  </table></div>
</div></div>
