<?php // app/view/branch_manager/stats.php ?>
<h2 class="mb-4"><i class="bi bi-bar-chart me-2 text-danger"></i>Cross-Branch Statistics</h2>
<div class="row g-4">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Borrow Stats Per Branch</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Branch</th><th>Active</th><th>Overdue</th></tr></thead>
          <tbody>
          <?php foreach ($statsPerBranch as $s): ?>
          <tr>
            <td><?= e($s['branch_name']) ?></td>
            <td><span class="badge bg-success"><?= $s['active_loans'] ?></span></td>
            <td><span class="badge bg-<?= $s['overdue_loans'] > 0 ? 'danger' : 'light text-muted' ?>"><?= $s['overdue_loans'] ?></span></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Outstanding Fines Per Branch</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Branch</th><th>Total Unpaid</th></tr></thead>
          <tbody>
          <?php foreach ($finesPerBranch as $f): ?>
          <tr><td><?= e($f['branch_name']) ?></td><td class="text-danger fw-bold">৳<?= number_format($f['total'], 2) ?></td></tr>
          <?php endforeach; ?>
          <?php if (empty($finesPerBranch)): ?>
          <tr><td colspan="2" class="text-success text-center py-3">No outstanding fines!</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Most Borrowed Books (All Branches)</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Title</th><th>Author</th><th>Borrows</th></tr></thead>
          <tbody>
          <?php foreach ($mostBorrowedAll as $b): ?>
          <tr>
            <td><?= e($b['title']) ?></td>
            <td><?= e($b['author']) ?></td>
            <td><span class="badge bg-primary"><?= $b['borrow_count'] ?></span></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Librarian Activity</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Librarian</th><th>Branch</th><th>Processed</th></tr></thead>
          <tbody>
          <?php foreach ($librarianActivity as $l): ?>
          <tr>
            <td><?= e($l['name']) ?></td>
            <td><?= e($l['branch_name']) ?></td>
            <td><span class="badge bg-info text-dark"><?= $l['processed'] ?></span></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Members with Outstanding Fines</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Member</th><th>Email</th><th>Total Fines</th></tr></thead>
          <tbody>
          <?php foreach ($membersWithFines as $m): ?>
          <tr>
            <td><?= e($m['name']) ?></td>
            <td><?= e($m['email']) ?></td>
            <td class="fw-bold text-danger">৳<?= number_format($m['total_fines'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($membersWithFines)): ?>
          <tr><td colspan="3" class="text-success text-center py-3">No members with outstanding fines.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
