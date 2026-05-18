<?php // app/view/member/fines.php
$unpaid = array_filter($fines, fn($f) => !$f['is_paid']);
$paid   = array_filter($fines, fn($f) => $f['is_paid']);
?>
<h2 class="mb-4"><i class="bi bi-cash-coin me-2 text-danger"></i>My Fines</h2>

<?php if (!empty($unpaid)): ?>
<div class="card border-0 shadow-sm border-start border-danger border-3 mb-4">
  <div class="card-header bg-white text-danger fw-semibold">Outstanding Fines</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-light"><tr><th>Book</th><th>Branch</th><th>Amount</th><th>Reason</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($unpaid as $f):
          $payReq = str_starts_with($f['reason'], '[PAYMENT REQUESTED]');
        ?>
        <tr>
          <td><?= e($f['book_title']) ?></td>
          <td><?= e($f['branch_name']) ?></td>
          <td class="fw-bold text-danger">৳<?= number_format($f['amount'],2) ?></td>
          <td><?= e(str_replace('[PAYMENT REQUESTED] ','',$f['reason'])) ?></td>
          <td><?= e(substr($f['created_at'],0,10)) ?></td>
          <td>
            <?php if ($payReq): ?>
              <span class="badge bg-info">Payment Pending Confirmation</span>
            <?php else: ?>
            <form method="POST" action="<?= BASE_URL ?>index.php?page=member_pay_fine">
              <input type="hidden" name="fine_id" value="<?= $f['id'] ?>">
              <button class="btn btn-sm btn-success">Indicate Payment</button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php else: ?>
<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>No outstanding fines!</div>
<?php endif; ?>

<?php if (!empty($paid)): ?>
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white fw-semibold text-muted">Paid Fine History</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-light"><tr><th>Book</th><th>Amount</th><th>Reason</th><th>Paid At</th></tr></thead>
        <tbody>
        <?php foreach ($paid as $f): ?>
        <tr>
          <td><?= e($f['book_title']) ?></td>
          <td class="text-success fw-bold">৳<?= number_format($f['amount'],2) ?></td>
          <td><?= e($f['reason']) ?></td>
          <td><?= e($f['paid_at'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php endif; ?>
