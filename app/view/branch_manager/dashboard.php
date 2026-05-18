<?php // app/view/branch_manager/dashboard.php ?>
<h2 class="mb-4"><i class="bi bi-speedometer2 me-2 text-danger"></i>Branch Manager Dashboard</h2>

<!-- Per-Branch Stats Table -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white fw-semibold"><i class="bi bi-building me-2 text-danger"></i>Branch Overview</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-danger">
          <tr><th>Branch</th><th class="text-center">Active Loans</th><th class="text-center">Overdue</th></tr>
        </thead>
        <tbody>
        <?php foreach ($statsPerBranch as $s): ?>
        <tr>
          <td class="fw-semibold"><?= e($s['branch_name']) ?></td>
          <td class="text-center"><span class="badge bg-success"><?= $s['active_loans'] ?></span></td>
          <td class="text-center">
            <?php if ($s['overdue_loans'] > 0): ?>
              <span class="badge bg-danger"><?= $s['overdue_loans'] ?></span>
            <?php else: ?>
              <span class="badge bg-light text-muted">0</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="row g-3">
  <!-- Outstanding Fines Per Branch -->
  <div class="col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white fw-semibold"><i class="bi bi-cash text-danger me-2"></i>Outstanding Fines by Branch</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light"><tr><th>Branch</th><th>Total Unpaid</th></tr></thead>
            <tbody>
            <?php foreach ($finesPerBranch as $f): ?>
            <tr>
              <td><?= e($f['branch_name']) ?></td>
              <td class="fw-bold text-danger">৳<?= number_format($f['total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($finesPerBranch)): ?>
            <tr><td colspan="2" class="text-muted text-center py-3">No outstanding fines.</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- All Overdue Loan -->
  <div class="col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white fw-semibold"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Overdue Loans (All Branches)</div>
      <div class="card-body p-0" style="max-height:300px;overflow-y:auto">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light"><tr><th>Member</th><th>Book</th><th>Due</th><th>Branch</th></tr></thead>
            <tbody>
            <?php foreach (array_slice($overdueAll, 0, 10) as $l): ?>
            <tr class="table-danger">
              <td><?= e($l['member_name']) ?></td>
              <td><?= e($l['book_title']) ?></td>
              <td class="fw-bold"><?= e($l['due_date']) ?></td>
              <td><?= e($l['branch_name']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($overdueAll)): ?>
            <tr><td colspan="4" class="text-success text-center py-3"><i class="bi bi-check-circle me-1"></i>No overdue loans!</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white"><a href="<?= BASE_URL ?>index.php?page=manager_stats" class="small">View full report →</a></div>
    </div>
  </div>
</div>

<!-- Quick Links -->
<div class="row g-2 mt-2">
  <?php $links = [
    ['manager_branches','bi-building','Manage Branches','outline-danger'],
    ['manager_policies','bi-gear','Branch Policies','outline-secondary'],
    ['manager_librarians','bi-person-badge','Librarians','outline-dark'],
    ['manager_transfers','bi-arrow-left-right','Transfers','outline-info'],
    ['manager_reports','bi-file-earmark-text','Reports','outline-warning'],
    ['manager_announce','bi-megaphone','Announce','outline-success'],
  ];
  foreach ($links as [$page, $icon, $label, $btn]): ?>
  <div class="col-6 col-md-2">
    <a href="<?= BASE_URL ?>index.php?page=<?= $page ?>" class="btn btn-<?= $btn ?> w-100 text-start">
      <i class="bi <?= $icon ?> me-1"></i><?= $label ?>
    </a>
  </div>
  <?php endforeach; ?>
</div>
