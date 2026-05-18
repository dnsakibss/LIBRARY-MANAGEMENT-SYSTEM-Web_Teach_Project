<?php // app/view/member/my_loans.php ?>
<h2 class="mb-4"><i class="bi bi-arrow-left-right me-2 text-primary"></i>My Loans</h2>

<!-- Active Loans -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white fw-semibold"><i class="bi bi-book-fill me-2 text-success"></i>Active Loans (<?= count($activeLoans) ?>)</div>
  <div class="card-body p-0">
    <?php if (empty($activeLoans)): ?>
      <p class="text-muted text-center py-4">No active loans.</p>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-light"><tr><th>Book</th><th>Branch</th><th>Borrowed</th><th>Due</th><th>Renewals</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($activeLoans as $l):
          $daysLeft = (int)floor((strtotime($l['due_date']) - time()) / 86400);
          $overdue  = $daysLeft < 0;
        ?>
        <tr class="<?= $overdue ? 'table-danger' : '' ?>">
          <td class="fw-semibold"><?= e($l['book_title']) ?></td>
          <td><?= e($l['branch_name']) ?></td>
          <td><?= e($l['borrow_date']) ?></td>
          <td class="<?= $overdue ? 'text-danger fw-bold' : '' ?>"><?= e($l['due_date']) ?></td>
          <td><?= $l['renewals_count'] ?></td>
          <td><?= $overdue ? '<span class="badge bg-danger">Overdue</span>' : "<span class='badge bg-success'>{$daysLeft}d left</span>" ?></td>
          <td>
            <form method="POST" action="<?= BASE_URL ?>index.php?page=member_renew" class="d-inline">
              <input type="hidden" name="record_id" value="<?= $l['id'] ?>">
              <button class="btn btn-xs btn-outline-primary btn-sm">Renew</button>
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

<!-- Pending Requests -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white fw-semibold"><i class="bi bi-hourglass-split me-2 text-warning"></i>Pending Requests (<?= count($pendingLoans) ?>)</div>
  <div class="card-body p-0">
    <?php if (empty($pendingLoans)): ?>
      <p class="text-muted text-center py-3">No pending requests.</p>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-light"><tr><th>Book</th><th>Branch</th><th>Requested</th></tr></thead>
        <tbody>
        <?php foreach ($pendingLoans as $l): ?>
        <tr><td class="fw-semibold"><?= e($l['book_title']) ?></td><td><?= e($l['branch_name']) ?></td><td><?= e($l['created_at']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- History -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white fw-semibold"><i class="bi bi-clock-history me-2 text-secondary"></i>Borrow History (<?= count($history) ?>)</div>
  <div class="card-body p-0">
    <?php if (empty($history)): ?>
      <p class="text-muted text-center py-3">No history yet.</p>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-light"><tr><th>Book</th><th>Branch</th><th>Borrowed</th><th>Returned</th></tr></thead>
        <tbody>
        <?php foreach ($history as $l): ?>
        <tr>
          <td><?= e($l['book_title']) ?></td>
          <td><?= e($l['branch_name']) ?></td>
          <td><?= e($l['borrow_date']) ?></td>
          <td><?= e($l['return_date'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
