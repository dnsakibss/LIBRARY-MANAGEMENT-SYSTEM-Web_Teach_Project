<?php // app/view/member/dashboard.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-speedometer2 me-2 text-primary"></i>My Dashboard</h2>
  <span class="text-muted small">Welcome, <?= e($_SESSION['user_name']) ?></span>
</div>

<!-- Stats row -->
<div class="row g-3 mb-4">
  <?php
  // $maxBooks is passed from the controller
  $remaining = max(0, $maxBooks - count($activeLoans));
  $cards = [
    ['Active Loans',    count($activeLoans) . ' / ' . $maxBooks,  'bi-book-fill',           count($activeLoans) >= $maxBooks ? 'danger' : 'primary'],
    ['Pending',         count($pendingLoans), 'bi-hourglass-split',      'warning'],
    ['Reservations',    count($reservations), 'bi-clock-history',        'info'],
    ['Unpaid Fines',    count($unpaidFines),  'bi-exclamation-triangle', 'danger'],
  ];
  foreach ($cards as [$label, $val, $icon, $color]): ?>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100 stat-card">
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
  <!-- Active loans -->
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold"><i class="bi bi-book me-2 text-primary"></i>Active Loans</div>
      <div class="card-body p-0">
        <?php if (empty($activeLoans)): ?>
          <p class="text-muted text-center py-4">No active loans.</p>
        <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light"><tr><th>Book</th><th>Branch</th><th>Due</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($activeLoans as $l):
              $daysLeft = (int)floor((strtotime($l['due_date']) - time()) / 86400);
              $overdue  = $daysLeft < 0;
            ?>
            <tr class="<?= $overdue ? 'table-danger' : '' ?>">
              <td class="fw-semibold"><?= e($l['book_title']) ?></td>
              <td><?= e($l['branch_name']) ?></td>
              <td><?= e($l['due_date']) ?></td>
              <td><?php if ($overdue): ?>
                <span class="badge bg-danger"><?= abs($daysLeft) ?>d overdue</span>
              <?php else: ?>
                <span class="badge bg-success"><?= $daysLeft ?>d left</span>
              <?php endif; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
      <div class="card-footer bg-white"><a href="<?= BASE_URL ?>index.php?page=member_my_loans" class="small">View all loans →</a></div>
    </div>
  </div>

  <!-- Announcements -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white fw-semibold"><i class="bi bi-megaphone me-2 text-warning"></i>Announcements</div>
      <div class="card-body p-0" style="max-height:300px;overflow-y:auto">
        <?php if (empty($announcements)): ?>
          <p class="text-muted text-center py-4">No announcements.</p>
        <?php else: foreach ($announcements as $a): ?>
        <div class="p-3 border-bottom">
          <strong class="small"><?= e($a['title']) ?></strong>
          <p class="small text-muted mb-1"><?= e(mb_strimwidth($a['body'], 0, 100, '…')) ?></p>
          <span class="badge bg-<?= $a['branch_id'] ? 'secondary' : 'primary' ?> small">
            <?= $a['branch_id'] ? e($a['branch_name']) : 'Platform-wide' ?>
          </span>
        </div>
        <?php endforeach; endif; ?>
      </div>
      <div class="card-footer bg-white"><a href="<?= BASE_URL ?>index.php?page=member_announcements" class="small">View all →</a></div>
    </div>
  </div>
</div>
