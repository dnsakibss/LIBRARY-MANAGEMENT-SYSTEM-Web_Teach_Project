<?php // app/view/admin/dashboard.php ?>
<h2 class="mb-4"><i class="bi bi-speedometer2 me-2 text-light"></i>Admin Dashboard</h2>

<div class="row g-3 mb-4">
  <?php $cards = [
    ['Total Members',       $stats['total_members'],     'bi-people-fill',            'primary'],
    ['Total Books',         $stats['total_books'],        'bi-journals',               'success'],
    ['Active Loans',        $stats['active_loans'],       'bi-book-fill',              'info'],
    ['Overdue',             $stats['overdue_loans'],      'bi-exclamation-triangle',   'danger'],
    ['Outstanding Fines ৳', number_format($stats['outstanding_fines'], 2), 'bi-cash-coin', 'warning'],
    ['Librarians',          $stats['librarians'],         'bi-person-badge',           'secondary'],
  ];
  foreach ($cards as [$label, $val, $icon, $color]): ?>
  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm stat-card h-100 border-top border-<?= $color ?> border-3">
      <div class="card-body text-center py-3">
        <i class="bi <?= $icon ?> fs-2 text-<?= $color ?>"></i>
        <h4 class="fw-bold my-1"><?= $val ?></h4>
        <p class="text-muted small mb-0"><?= $label ?></p>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Quick nav -->
<div class="row g-2 mb-4">
  <?php $links = [
    ['admin_users',       'bi-people',        'Manage Users',    'primary'],
    ['admin_user_add',    'bi-person-plus',   'Create Staff',    'success'],
    ['admin_branches',    'bi-building',      'Branches',        'danger'],
    ['admin_books',       'bi-journals',      'Books',           'info'],
    ['admin_transfers',   'bi-arrow-left-right','Transfers',     'secondary'],
    ['admin_reports',     'bi-graph-up',      'Reports',         'warning'],
    ['admin_settings',    'bi-sliders',       'Settings',        'dark'],
    ['admin_audit',       'bi-clock-history', 'Audit Log',       'outline-dark'],
  ];
  foreach ($links as [$page, $icon, $label, $btn]): ?>
  <div class="col-6 col-md-3 col-lg-auto">
    <a href="<?= BASE_URL ?>index.php?page=<?= $page ?>" class="btn btn-<?= $btn ?> w-100">
      <i class="bi <?= $icon ?> me-1"></i><?= $label ?>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<!-- Recent active loans -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white fw-semibold"><i class="bi bi-list-check me-2"></i>Current Active Loans (All Branches)</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-dark">
          <tr><th>Member</th><th>Book</th><th>Branch</th><th>Due</th><th>Status</th></tr>
        </thead>
        <tbody>
        <?php foreach (array_slice($recentActivity, 0, 10) as $l):
          $overdue = $l['due_date'] < date('Y-m-d');
        ?>
        <tr class="<?= $overdue ? 'table-danger' : '' ?>">
          <td><?= e($l['member_name']) ?></td>
          <td><?= e($l['book_title']) ?></td>
          <td><?= e($l['branch_name']) ?></td>
          <td><?= e($l['due_date']) ?></td>
          <td><?= $overdue ? '<span class="badge bg-danger">Overdue</span>' : '<span class="badge bg-success">On Time</span>' ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($recentActivity)): ?>
        <tr><td colspan="5" class="text-muted text-center py-3">No active loans.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer bg-white"><a href="<?= BASE_URL ?>index.php?page=admin_reports" class="small">Full reports →</a></div>
</div>
