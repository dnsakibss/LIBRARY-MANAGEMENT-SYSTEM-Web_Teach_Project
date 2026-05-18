<?php // app/view/librarian/fines.php ?>

<h2 class="mb-4">
  <i class="bi bi-cash-coin me-2 text-success"></i>Manage Fines
</h2>

<div class="row g-4">

  <!-- Manual fine section for damaged/lost books -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm">
      <div class="card-body">

        <h6 class="fw-semibold mb-3 text-danger">
          <i class="bi bi-plus-circle me-1"></i>Issue Manual Fine
        </h6>

        <form method="POST">
          <input type="hidden" name="action" value="manual_fine">

          <div class="mb-2">
            <label class="form-label small fw-semibold">Borrow Record ID</label>
            <input type="number"
                   name="record_id"
                   class="form-control form-control-sm"
                   placeholder="Record #"
                   required>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold">Amount (৳)</label>
            <input type="number"
                   name="amount"
                   class="form-control form-control-sm"
                   step="0.01"
                   min="1"
                   required>
          </div>

          <div class="mb-2">
            <label class="form-label small fw-semibold">Reason</label>
            <input type="text"
                   name="reason"
                   class="form-control form-control-sm"
                   value="Damaged/Lost"
                   required>
          </div>

          <button class="btn btn-danger btn-sm w-100">
            Issue Fine
          </button>
        </form>

      </div>
    </div>
  </div>

  <!-- Fine history / payment tracking -->
  <div class="col-md-8">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 small">

            <thead class="table-light">
              <tr>
                <th>Member</th>
                <th>Book</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>

            <?php foreach($fines as $f): ?>
            <tr>

              <td><?= e($f['member_name']) ?></td>

              <td><?= e($f['book_title']) ?></td>

              <td class="fw-bold <?= $f['is_paid'] ? 'text-success' : 'text-danger' ?>">
                ৳<?= number_format($f['amount'], 2) ?>
              </td>

              <!-- Keep reason text short so table stays clean -->
              <td class="small">
                <?= e(mb_strimwidth($f['reason'], 0, 40, '…')) ?>
              </td>

              <td>
                <?= $f['is_paid']
                  ? '<span class="badge bg-success">Paid</span>'
                  : '<span class="badge bg-danger">Unpaid</span>' ?>
              </td>

              <td>
                <?php if(!$f['is_paid']): ?>

                <form method="POST">
                  <input type="hidden" name="action" value="mark_paid">
                  <input type="hidden" name="fine_id" value="<?= $f['id'] ?>">

                  <button class="btn btn-xs btn-sm btn-success">
                    Mark Paid
                  </button>
                </form>

                <?php else: ?>

                  <!-- Show payment date once fine is cleared -->
                  <small class="text-muted">
                    <?= e(substr($f['paid_at'], 0, 10)) ?>
                  </small>

                <?php endif; ?>
              </td>

            </tr>
            <?php endforeach; ?>

            </tbody>

          </table>
        </div>

      </div>
    </div>
  </div>

</div>