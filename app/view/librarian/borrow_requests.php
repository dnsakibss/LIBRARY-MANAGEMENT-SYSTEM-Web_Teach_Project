<?php // app/view/librarian/borrow_requests.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-inbox me-2 text-warning"></i>Pending Borrow Requests</h2>
  <span class="badge bg-warning text-dark fs-6"><?= count($requests) ?> pending</span>
</div>

<?php if (!empty($noBranchWarning)): ?>
<div class="alert alert-warning">
  <i class="bi bi-exclamation-triangle me-2"></i>
  <strong>No branch assigned to your account.</strong>
  Showing all pending requests. Ask an admin to assign you to a branch.
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <?php if (empty($requests)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
        <p class="mb-0">No pending borrow requests at your branch.</p>
        <?php if (!empty($noBranchWarning)): ?>
          <small>No pending requests in any branch.</small>
        <?php endif; ?>
      </div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-warning">
          <tr>
            <th>#</th>
            <th>Member</th>
            <th>Book</th>
            <th>Branch</th>
            <th>Requested</th>
            <th>Copies Left</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php
        require_once BASE_PATH . '/app/model/BookModel.php';
        $bm = new BookModel($conn);
        foreach ($requests as $i => $r):
          $avail = $bm->availableCopies((int)$r['book_id'], (int)$r['branch_id']);
        ?>
        <tr>
          <td class="text-muted small"><?= $i + 1 ?></td>
          <td>
            <strong><?= e($r['member_name']) ?></strong><br>
            <small class="text-muted"><?= e($r['member_email']) ?></small><br>
            <small class="text-muted"><i class="bi bi-telephone me-1"></i><?= e($r['member_phone'] ?? '—') ?></small>
          </td>
          <td>
            <strong><?= e($r['book_title']) ?></strong><br>
            <small class="text-muted"><?= e($r['book_author']) ?></small>
          </td>
          <td><span class="badge bg-secondary"><?= e($r['branch_name']) ?></span></td>
          <td class="small"><?= e(substr($r['created_at'], 0, 16)) ?></td>
          <td>
            <?php if ($avail > 0): ?>
              <span class="badge bg-success"><?= $avail ?> available</span>
            <?php else: ?>
              <span class="badge bg-danger">None available</span>
            <?php endif; ?>
          </td>
          <td>
            <form method="POST" class="d-inline-flex gap-1">
              <input type="hidden" name="record_id" value="<?= $r['id'] ?>">
              <?php if ($avail > 0): ?>
              <button name="action" value="approve" class="btn btn-sm btn-success">
                <i class="bi bi-check me-1"></i>Approve
              </button>
              <?php else: ?>
              <button name="action" value="approve" class="btn btn-sm btn-success" disabled title="No copies available">
                <i class="bi bi-check me-1"></i>Approve
              </button>
              <?php endif; ?>
              <button name="action" value="reject" class="btn btn-sm btn-danger"
                onclick="return confirm('Reject this request from <?= e(addslashes($r['member_name'])) ?>?')">
                <i class="bi bi-x me-1"></i>Reject
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer bg-white text-muted small">
      <?= count($requests) ?> pending request(s)
    </div>
    <?php endif; ?>
  </div>
</div>
