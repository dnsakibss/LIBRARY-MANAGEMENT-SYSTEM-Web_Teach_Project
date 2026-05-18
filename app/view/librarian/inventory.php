<?php // app/view/librarian/inventory.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-archive me-2 text-success"></i>Branch Inventory</h2>
  <span class="badge bg-success fs-6"><?= count($inventory) ?> books</span>
</div>

<!-- Quick Add Copies -->
<div class="row g-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="bi bi-plus-circle me-2 text-success"></i>Update Stock
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Book</label>
            <select name="book_id" class="form-select form-select-sm" required>
              <option value="">-- Select Book --</option>
              <?php foreach ($books as $b): ?>
              <option value="<?= $b['id'] ?>"><?= e($b['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold">Total Copies</label>
              <input type="number" name="total" class="form-control form-control-sm" min="0" value="1" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Available Now</label>
              <input type="number" name="available" class="form-control form-control-sm" min="0" value="1" required>
            </div>
          </div>
          <div class="form-text mb-3 text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Available = Total minus currently borrowed copies.
          </div>
          <button class="btn btn-success btn-sm w-100">
            <i class="bi bi-save me-1"></i>Update Stock
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Inventory Table -->
  <div class="col-md-8">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="bi bi-table me-2 text-success"></i>
        Stock at Your Branch
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 small">
            <thead class="table-success">
              <tr>
                <th>Title</th>
                <th>Author</th>
                <th class="text-center">Available</th>
                <th class="text-center">Total</th>
                <th class="text-center">Borrowed</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($inventory)): ?>
              <tr><td colspan="6" class="text-muted text-center py-4">No inventory at this branch.</td></tr>
            <?php else: foreach ($inventory as $inv):
              $borrowed = $inv['total_copies'] - $inv['available_copies'];
              $pct      = $inv['total_copies'] > 0 ? ($inv['available_copies'] / $inv['total_copies'] * 100) : 0;
            ?>
            <tr>
              <td class="fw-semibold"><?= e($inv['title']) ?></td>
              <td class="text-muted"><?= e($inv['author']) ?></td>
              <td class="text-center">
                <span class="badge bg-<?= $inv['available_copies'] > 0 ? 'success' : 'danger' ?> fs-6">
                  <?= $inv['available_copies'] ?>
                </span>
              </td>
              <td class="text-center"><?= $inv['total_copies'] ?></td>
              <td class="text-center">
                <?php if ($borrowed > 0): ?>
                  <span class="badge bg-warning text-dark"><?= $borrowed ?></span>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td class="text-center" style="min-width:100px">
                <div class="progress" style="height:8px">
                  <div class="progress-bar bg-<?= $pct > 50 ? 'success' : ($pct > 0 ? 'warning' : 'danger') ?>"
                       style="width:<?= $pct ?>%"></div>
                </div>
                <small class="text-muted"><?= round($pct) ?>% available</small>
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
