<?php // app/view/librarian/dashboard.php ?>
<?php
$branchName = '';
if (!empty($_SESSION['branch_id'])) {
    $bm = new BranchModel($conn);
    $br = $bm->getById((int)$_SESSION['branch_id']);
    $branchName = $br ? ' — ' . e($br['name']) : '';
}
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-speedometer2 me-2 text-success"></i>Librarian Dashboard<?= $branchName ?></h2>
  <?php if (empty($_SESSION['branch_id'])): ?>
  <span class="badge bg-danger">No Branch Assigned — Contact Admin</span>
  <?php endif; ?>
</div>
<div class="row g-3 mb-4">
  <?php $cards=[['Pending Requests',count($pendingRequests),'bi-inbox','warning'],['Active Loans',count($activeLoans),'bi-book-fill','success'],['Overdue',count($overdueLoans),'bi-exclamation-triangle','danger'],['Unpaid Fines',count($unpaidFines),'bi-cash','info']];
  foreach($cards as [$label,$val,$icon,$color]): ?>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm stat-card h-100">
      <div class="card-body text-center py-3">
        <i class="bi <?= $icon ?> fs-2 text-<?= $color ?>"></i>
        <h3 class="fw-bold my-1"><?= $val ?></h3>
        <p class="text-muted small mb-0"><?= $label ?></p>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<div class="row g-3">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold text-warning"><i class="bi bi-inbox me-2"></i>Pending Borrow Requests</div>
      <div class="card-body p-0">
        <?php if(empty($pendingRequests)): ?><p class="text-muted text-center py-4">No pending requests.</p>
        <?php else: ?>
        <div class="table-responsive"><table class="table table-hover align-middle mb-0 small">
          <thead class="table-light"><tr><th>Member</th><th>Book</th><th>Date</th><th>Action</th></tr></thead>
          <tbody>
          <?php foreach(array_slice($pendingRequests,0,5) as $r): ?>
          <tr>
            <td><?= e($r['member_name']) ?></td>
            <td><?= e($r['book_title']) ?></td>
            <td><?= e(substr($r['created_at'],0,10)) ?></td>
            <td>
              <form method="POST" action="<?= BASE_URL ?>index.php?page=librarian_requests" class="d-inline">
                <input type="hidden" name="record_id" value="<?= $r['id'] ?>">
                <button name="action" value="approve" class="btn btn-xs btn-success btn-sm">Approve</button>
                <button name="action" value="reject"  class="btn btn-xs btn-danger btn-sm">Reject</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table></div>
        <?php endif; ?>
      </div>
      <div class="card-footer bg-white"><a href="<?= BASE_URL ?>index.php?page=librarian_requests" class="small">View all →</a></div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Overdue Loans</div>
      <div class="card-body p-0">
        <?php if(empty($overdueLoans)): ?><p class="text-success text-center py-4"><i class="bi bi-check-circle me-2"></i>No overdue loans!</p>
        <?php else: ?>
        <div class="table-responsive"><table class="table table-hover align-middle mb-0 small">
          <thead class="table-light"><tr><th>Member</th><th>Book</th><th>Due Date</th></tr></thead>
          <tbody>
          <?php foreach(array_slice($overdueLoans,0,5) as $l): ?>
          <tr class="table-danger"><td><?= e($l['member_name']) ?></td><td><?= e($l['book_title']) ?></td><td class="fw-bold"><?= e($l['due_date']) ?></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table></div>
        <?php endif; ?>
      </div>
      <div class="card-footer bg-white"><a href="<?= BASE_URL ?>index.php?page=librarian_loans&filter=overdue" class="small">View all →</a></div>
    </div>
  </div>
</div>
