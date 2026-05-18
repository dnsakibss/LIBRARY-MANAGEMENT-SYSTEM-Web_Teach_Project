<?php // app/view/branch_manager/reports.php ?>
<h2 class="mb-4"><i class="bi bi-file-earmark-text me-2 text-danger"></i>Monthly Reports</h2>

<div class="mb-3">
  <form method="GET" action="<?= BASE_URL ?>index.php" class="d-inline-flex gap-2 align-items-center">
    <input type="hidden" name="page" value="manager_reports">
    <label class="fw-semibold me-2">Branch:</label>
    <select name="branch_id" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
      <?php foreach ($branches as $b): ?>
      <option value="<?= $b['id'] ?>" <?= $selectedBranch == $b['id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </form>
</div>

<div class="row g-4">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Monthly Borrows & Returns</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Month</th><th>Borrows</th><th>Returns</th></tr></thead>
          <tbody>
          <?php foreach ($monthlyBorrows as $m): ?>
          <tr>
            <td><?= e($m['month']) ?></td>
            <td><span class="badge bg-primary"><?= $m['borrows'] ?></span></td>
            <td><span class="badge bg-success"><?= $m['returns'] ?></span></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($monthlyBorrows)): ?>
          <tr><td colspan="3" class="text-muted text-center py-3">No data.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Monthly Fines Collected (All Branches)</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Month</th><th>Collected (৳)</th></tr></thead>
          <tbody>
          <?php foreach ($finesCollected as $m): ?>
          <tr>
            <td><?= e($m['month']) ?></td>
            <td class="fw-bold text-success">৳<?= number_format($m['total'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($finesCollected)): ?>
          <tr><td colspan="2" class="text-muted text-center py-3">No fines collected yet.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">New Member Registrations Per Branch (Last 30 Days)</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 small">
          <thead class="table-light"><tr><th>Branch</th><th>New Members</th></tr></thead>
          <tbody>
          <?php foreach ($newMembersPerBranch as $row): ?>
          <tr>
            <td><?= e($row['branch_name']) ?></td>
            <td><span class="badge bg-info text-dark"><?= $row['new_members'] ?? $row['count'] ?? 0 ?></span></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
