<?php // app/view/librarian/reservations.php ?>
<h2 class="mb-4"><i class="bi bi-clock me-2 text-success"></i>Reservation Waitlist</h2>
<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <?php if (empty($reservations)): ?>
      <p class="text-muted text-center py-5">No active reservations at this branch.</p>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-success">
          <tr><th>#</th><th>Member</th><th>Book</th><th>Reserved At</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $i => $r): ?>
        <tr>
          <td class="text-muted"><?= $i + 1 ?></td>
          <td>
            <strong><?= e($r['member_name']) ?></strong><br>
            <small class="text-muted"><?= e($r['member_email']) ?></small>
          </td>
          <td><?= e($r['book_title']) ?></td>
          <td class="small"><?= e(substr($r['reserved_at'], 0, 10)) ?></td>
          <td>
            <form method="POST" onsubmit="return confirm('Mark as fulfilled and notify member?')">
              <input type="hidden" name="reservation_id" value="<?= $r['id'] ?>">
              <button class="btn btn-sm btn-success"><i class="bi bi-check me-1"></i>Fulfil</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
