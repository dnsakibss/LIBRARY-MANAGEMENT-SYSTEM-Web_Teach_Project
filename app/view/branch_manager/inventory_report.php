<?php // app/view/branch_manager/inventory_report.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-archive me-2 text-danger"></i>Inventory Management</h2>
</div>

<div class="row g-4">
  <!-- Quick Update For -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold text-danger">
        <i class="bi bi-pencil-square me-2"></i>Update Stock
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Branch</label>
            <select name="branch_id" class="form-select form-select-sm" required>
              <option value="">-- Select Branch --</option>
              <?php foreach ($branches as $b): if (!$b['is_active']) continue; ?>
              <option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Book</label>
            <select name="book_id" class="form-select form-select-sm" required>
              <option value="">-- Select Book --</option>
              <?php foreach ($allBooks as $b): ?>
              <option value="<?= $b['id'] ?>"><?= e($b['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold">Total Copies</label>
              <input type="number" name="total" class="form-control form-control-sm" min="1" value="1" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Available Now</label>
              <input type="number" name="available" class="form-control form-control-sm" min="0" value="1" required>
            </div>
          </div>
          <div class="alert alert-info py-2 small">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Available</strong> = Total minus currently borrowed
          </div>
          <button class="btn btn-danger btn-sm w-100">
            <i class="bi bi-save me-1"></i>Update Inventory
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Cross-Branch Inventory Table -->
  <div class="col-md-8">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="bi bi-table me-2 text-danger"></i>All Branches Inventory
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 small">
            <thead class="table-danger">
              <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Branch</th>
                <th class="text-center">Available</th>
                <th class="text-center">Total</th>
                <th class="text-center">Borrowed</th>
                <th class="text-center">Stock</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($inventory)): ?>
              <tr><td colspan="7" class="text-muted text-center py-4">No inventory data.</td></tr>
            <?php else: foreach ($inventory as $row):
              $borrowed = $row['total_copies'] - $row['available_copies'];
              $pct = $row['total_copies'] > 0 ? ($row['available_copies'] / $row['total_copies'] * 100) : 0;
            ?>
            <tr>
              <td class="fw-semibold"><?= e($row['title']) ?></td>
              <td class="text-muted"><?= e($row['author']) ?></td>
              <td><span class="badge bg-secondary"><?= e($row['branch_name']) ?></span></td>
              <td class="text-center">
                <span class="badge bg-<?= $row['available_copies'] > 0 ? 'success' : 'danger' ?> fs-6">
                  <?= $row['available_copies'] ?>
                </span>
              </td>
              <td class="text-center"><?= $row['total_copies'] ?></td>
              <td class="text-center">
                <?= $borrowed > 0 ? "<span class='badge bg-warning text-dark'>$borrowed</span>" : '<span class="text-muted">—</span>' ?>
              </td>
              <td style="min-width:80px">
                <div class="progress" style="height:6px">
                  <div class="progress-bar bg-<?= $pct > 50 ? 'success' : ($pct > 0 ? 'warning' : 'danger') ?>"
                       style="width:<?= $pct ?>%"></div>
                </div>
                <small class="text-muted"><?= round($pct) ?>%</small>
              </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
