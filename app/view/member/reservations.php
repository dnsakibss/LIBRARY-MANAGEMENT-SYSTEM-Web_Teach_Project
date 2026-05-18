<?php // app/view/member/reservations.php ?>
<h2 class="mb-4"><i class="bi bi-clock me-2 text-info"></i>My Reservations</h2>
<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <?php if (empty($reservations)): ?>
      <p class="text-muted text-center py-5">No reservations.</p>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-info"><tr><th>Book</th><th>Branch</th><th>Reserved At</th><th>Queue Position</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($reservations as $r): ?>
        <tr>
          <td class="fw-semibold"><?= e($r['book_title']) ?></td>
          <td><?= e($r['branch_name']) ?></td>
          <td class="small"><?= e($r['reserved_at']) ?></td>
          <td><span class="badge bg-info text-dark">#<?= $r['queue_position'] ?></span></td>
          <td>
            <?php if ($r['status']==='waiting'): ?><span class="badge bg-warning text-dark">Waiting</span>
            <?php elseif ($r['status']==='fulfilled'): ?><span class="badge bg-success">Fulfilled</span>
            <?php else: ?><span class="badge bg-secondary">Cancelled</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($r['status']==='waiting'): ?>
            <form method="POST">
              <input type="hidden" name="cancel_id" value="<?= $r['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel reservation?')">Cancel</button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
