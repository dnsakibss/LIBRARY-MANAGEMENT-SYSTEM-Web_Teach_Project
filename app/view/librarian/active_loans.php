<?php // app/view/librarian/active_loans.php ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0"><i class="bi bi-list-check me-2 text-success"></i>Active Loans</h2>
</div>
<div class="mb-3 d-flex gap-2 flex-wrap">
  <a href="<?= BASE_URL ?>index.php?page=librarian_loans" class="btn btn-sm btn-<?= $filter===''?'success':'outline-success' ?>">All Active</a>
  <a href="<?= BASE_URL ?>index.php?page=librarian_loans&filter=overdue" class="btn btn-sm btn-<?= $filter==='overdue'?'danger':'outline-danger' ?>">Overdue</a>
  <a href="<?= BASE_URL ?>index.php?page=librarian_loans&filter=due_today" class="btn btn-sm btn-<?= $filter==='due_today'?'warning':'outline-warning' ?>">Due Today</a>
  <a href="<?= BASE_URL ?>index.php?page=librarian_loans&filter=due_week" class="btn btn-sm btn-<?= $filter==='due_week'?'info':'outline-info' ?>">Due This Week</a>
</div>
<div class="card border-0 shadow-sm"><div class="card-body p-0">
  <?php if(empty($loans)): ?><p class="text-muted text-center py-5">No loans match this filter.</p>
  <?php else: ?>
  <div class="table-responsive"><table class="table table-hover align-middle mb-0 small">
    <thead class="table-success"><tr><th>Member</th><th>Book</th><th>Borrowed</th><th>Due</th><th>Renewals</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach($loans as $l):
      $overdue = $l['due_date'] < date('Y-m-d');
    ?>
    <tr class="<?= $overdue?'table-danger':'' ?>">
      <td><?= e($l['member_name']) ?></td>
      <td><?= e($l['book_title']) ?></td>
      <td><?= e($l['borrow_date']) ?></td>
      <td class="<?= $overdue?'fw-bold':'' ?>"><?= e($l['due_date']) ?></td>
      <td><?= $l['renewals_count'] ?></td>
      <td><?= $overdue?'<span class="badge bg-danger">Overdue</span>':'<span class="badge bg-success">On Time</span>' ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <div class="card-footer bg-white text-muted small">Showing <?= count($loans) ?> loan(s)</div>
  <?php endif; ?>
</div></div>
