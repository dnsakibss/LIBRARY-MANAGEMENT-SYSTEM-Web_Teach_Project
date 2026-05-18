<?php // app/view/branch_manager/members_report.php ?>
<h2 class="mb-4"><i class="bi bi-people me-2 text-danger"></i>Member Activity Reports</h2>
<div class="row g-4">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Most Active Members (All Borrows)</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Name</th><th>Branch</th><th>Borrows</th></tr></thead>
          <tbody>
          <?php foreach ($activeMembers as $m): ?>
          <tr>
            <td><?= e($m['name']) ?></td>
            <td><?= e($m['branch_name']) ?></td>
            <td><span class="badge bg-primary"><?= $m['borrow_count'] ?></span></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Members with Outstanding Fines</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Name</th><th>Branch</th><th>Total (৳)</th></tr></thead>
          <tbody>
          <?php foreach ($membersWithFines as $m): ?>
          <tr>
            <td><?= e($m['name']) ?></td>
            <td><?= e($m['branch_name']) ?></td>
            <td class="text-danger fw-bold">৳<?= number_format($m['total_fines'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($membersWithFines)): ?>
          <tr><td colspan="3" class="text-success text-center py-3">No outstanding fines.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Total Registered Members Per Branch</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Branch</th><th>Total Members</th></tr></thead>
          <tbody>
          <?php foreach ($regPerBranch as $r): ?>
          <tr>
            <td><?= e($r['branch_name']) ?></td>
            <td><span class="badge bg-info text-dark"><?= $r['count'] ?></span></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
