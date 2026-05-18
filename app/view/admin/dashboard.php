<?php // app/view/admin/dashboard.php ?>

<h2 class="mb-4"><i class="bi bi-speedometer2 me-2 text-light"></i>Admin Dashboard</h2>

<div class="row g-3 mb-4">

  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm stat-card h-100 border-top border-primary border-3">
      <div class="card-body text-center py-3">
        <i class="bi bi-people-fill fs-2 text-primary"></i>
        <h4 class="fw-bold my-1"><?= e($stats['total_members']) ?></h4>
        <p class="text-muted small mb-0">Total Members</p>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm stat-card h-100 border-top border-success border-3">
      <div class="card-body text-center py-3">
        <i class="bi bi-journals fs-2 text-success"></i>
        <h4 class="fw-bold my-1"><?= e($stats['total_books']) ?></h4>
        <p class="text-muted small mb-0">Total Books</p>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm stat-card h-100 border-top border-info border-3">
      <div class="card-body text-center py-3">
        <i class="bi bi-book-fill fs-2 text-info"></i>
        <h4 class="fw-bold my-1"><?= e($stats['active_loans']) ?></h4>
        <p class="text-muted small mb-0">Active Loans</p>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm stat-card h-100 border-top border-danger border-3">
      <div class="card-body text-center py-3">
        <i class="bi bi-exclamation-triangle fs-2 text-danger"></i>
        <h4 class="fw-bold my-1"><?= e($stats['overdue_loans']) ?></h4>
        <p class="text-muted small mb-0">Overdue</p>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm stat-card h-100 border-top border-warning border-3">
      <div class="card-body text-center py-3">
        <i class="bi bi-cash-coin fs-2 text-warning"></i>
        <h4 class="fw-bold my-1"><?= number_format($stats['outstanding_fines'], 2) ?></h4>
        <p class="text-muted small mb-0">Outstanding Fines ৳</p>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm stat-card h-100 border-top border-secondary border-3">
      <div class="card-body text-center py-3">
        <i class="bi bi-person-badge fs-2 text-secondary"></i>
        <h4 class="fw-bold my-1"><?= e($stats['librarians']) ?></h4>
        <p class="text-muted small mb-0">Librarians</p>
      </div>
    </div>
  </div>

</div>


<div class="row g-2 mb-4">

  <div class="col-6 col-md-3 col-lg-auto">
    <a href="<?= BASE_URL ?>index.php?page=admin_users" class="btn btn-primary w-100">
      <i class="bi bi-people me-1"></i>Manage Users
    </a>
  </div>

  <div class="col-6 col-md-3 col-lg-auto">
    <a href="<?= BASE_URL ?>index.php?page=admin_user_add" class="btn btn-success w-100">
      <i class="bi bi-person-plus me-1"></i>Create Staff
    </a>
  </div>

  <div class="col-6 col-md-3 col-lg-auto">
    <a href="<?= BASE_URL ?>index.php?page=admin_branches" class="btn btn-danger w-100">
      <i class="bi bi-building me-1"></i>Branches
    </a>
  </div>

  <div class="col-6 col-md-3 col-lg-auto">
    <a href="<?= BASE_URL ?>index.php?page=admin_books" class="btn btn-info w-100">
      <i class="bi bi-journals me-1"></i>Books
    </a>
  </div>

  <div class="col-6 col-md-3 col-lg-auto">
    <a href="<?= BASE_URL ?>index.php?page=admin_transfers" class="btn btn-secondary w-100">
      <i class="bi bi-arrow-left-right me-1"></i>Transfers
    </a>
  </div>

  <div class="col-6 col-md-3 col-lg-auto">
    <a href="<?= BASE_URL ?>index.php?page=admin_reports" class="btn btn-warning w-100">
      <i class="bi bi-graph-up me-1"></i>Reports
    </a>
  </div>

  <div class="col-6 col-md-3 col-lg-auto">
    <a href="<?= BASE_URL ?>index.php?page=admin_settings" class="btn btn-dark w-100">
      <i class="bi bi-sliders me-1"></i>Settings
    </a>
  </div>

  <div class="col-6 col-md-3 col-lg-auto">
    <a href="<?= BASE_URL ?>index.php?page=admin_audit" class="btn btn-outline-dark w-100">
      <i class="bi bi-clock-history me-1"></i>Audit Log
    </a>
  </div>

</div>


<div class="card border-0 shadow-sm">
  <div class="card-header bg-white fw-semibold">
    <i class="bi bi-list-check me-2"></i>Current Active Loans (All Branches)
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-dark">
          <tr>
            <th>Member</th>
            <th>Book</th>
            <th>Branch</th>
            <th>Due</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php 
        // Take only the first 10 loan entries from the array data
        $limitedLoans = array_slice($recentActivity, 0, 10); 
        
        // Loop through each loan record using a basic descriptive foreach loop
        foreach ($limitedLoans as $loan): 
          
          // Basic logic check to determine if the book is overdue
          $todayDate = date('Y-m-d');
          $isOverdue = false;
          if ($loan['due_date'] < $todayDate) {
              $isOverdue = true;
          }
          
          // Assign row highlighting class explicitly based on validation check
          $rowBgClass = "";
          if ($isOverdue == true) {
              $rowBgClass = "table-danger";
          }
        ?>
        <tr class="<?= $rowBgClass ?>">
          <td><?= e($loan['member_name']) ?></td>
          <td><?= e($loan['book_title']) ?></td>
          <td><?= e($loan['branch_name']) ?></td>
          <td><?= e($loan['due_date']) ?></td>
          <td>
            <?php if ($isOverdue == true): ?>
              <span class="badge bg-danger">Overdue</span>
            <?php else: ?>
              <span class="badge bg-success">On Time</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>

        <?php if (empty($recentActivity)): ?>
        <tr>
          <td colspan="5" class="text-muted text-center py-3">No active loans.</td>
        </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer bg-white">
    <a href="<?= BASE_URL ?>index.php?page=admin_reports" class="small">Full reports →</a>
  </div>
</div>