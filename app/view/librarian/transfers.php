<?php // app/view/librarian/transfers.php ?>

<h2 class="mb-4">
  <i class="bi bi-arrow-left-right me-2 text-success"></i>Inter-Branch Transfers
</h2>

<div class="row g-4">

  <!-- Left panel: create a new transfer request -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm">
      <div class="card-body">

        <h6 class="fw-semibold mb-3">New Transfer Request</h6>

        <form method="POST">
          <input type="hidden" name="action" value="create">

          <div class="mb-2">
            <label class="form-label small fw-semibold">Book</label>

            <!-- Book selection list (only existing catalog items) -->
            <select name="book_id" class="form-select form-select-sm" required>
              <option value="">-- Select Book --</option>
              <?php foreach ($books as $b): ?>
                <option value="<?= $b['id'] ?>"><?= e($b['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold">To Branch</label>

            <!-- Prevents selecting current branch as destination -->
            <select name="to_branch_id" class="form-select form-select-sm" required>
              <option value="">-- Select Branch --</option>
              <?php foreach ($branches as $b): if ($b['id'] == $_SESSION['branch_id']) continue; ?>
                <option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <button class="btn btn-success btn-sm w-100">
            <i class="bi bi-send me-1"></i>Request Transfer
          </button>

        </form>
      </div>
    </div>
  </div>

  <!-- Right panel: transfer tracking table -->
  <div class="col-md-8">
    <div class="card border-0 shadow-sm">

      <div class="card-header bg-white fw-semibold">
        Transfer Requests Involving This Branch
      </div>

      <div class="card-body p-0">

        <?php if (empty($transfers)): ?>

          <p class="text-muted text-center py-4">
            No transfer requests.
          </p>

        <?php else: ?>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 small">

            <thead class="table-light">
              <tr>
                <th>Book</th>
                <th>From</th>
                <th>To</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>

              <?php foreach ($transfers as $t): ?>

              <tr>
                <td><?= e($t['book_title']) ?></td>
                <td><?= e($t['from_branch']) ?></td>
                <td><?= e($t['to_branch']) ?></td>

                <td>
                  <?php
                    // Simple status-to-color mapping for UI clarity
                    $badgeMap = [
                      'pending'   => 'warning',
                      'approved'  => 'success',
                      'rejected'  => 'danger',
                      'completed' => 'secondary'
                    ];
                    $cls = $badgeMap[$t['status']] ?? 'secondary';
                  ?>

                  <span class="badge bg-<?= $cls ?> text-<?= $cls==='warning'?'dark':'white' ?>">
                    <?= ucfirst($t['status']) ?>
                  </span>
                </td>

                <td>

                  <!-- Only receiving branch can approve/reject pending requests -->
                  <?php if ($t['status'] === 'pending' && $t['to_branch_id'] == $_SESSION['branch_id']): ?>

                    <form method="POST" class="d-inline-flex gap-1">
                      <input type="hidden" name="action" value="update">
                      <input type="hidden" name="transfer_id" value="<?= $t['id'] ?>">

                      <button name="status" value="approved" class="btn btn-xs btn-sm btn-success">
                        Approve
                      </button>

                      <button name="status" value="rejected" class="btn btn-xs btn-sm btn-danger">
                        Reject
                      </button>
                    </form>

                  <!-- Once approved, sender branch can mark completion -->
                  <?php elseif ($t['status'] === 'approved' && $t['from_branch_id'] == $_SESSION['branch_id']): ?>

                    <form method="POST" class="d-inline">
                      <input type="hidden" name="action" value="update">
                      <input type="hidden" name="transfer_id" value="<?= $t['id'] ?>">

                      <button name="status" value="completed" class="btn btn-xs btn-sm btn-info">
                        Mark Complete
                      </button>
                    </form>

                  <?php else: ?>

                    <span class="text-muted">—</span>

                  <?php endif; ?>

                </td>
              </tr>

              <?php endforeach; ?>

            </tbody>
          </table>
        </div>

        <?php endif; ?>

      </div>
    </div>
  </div>

</div>