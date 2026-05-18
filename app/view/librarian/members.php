<?php // app/view/librarian/members.php ?>

<h2 class="mb-4">
  <i class="bi bi-people me-2 text-success"></i>Member Records
</h2>

<!-- Simple member search -->
<form method="GET" action="<?= BASE_URL ?>index.php" class="mb-3 d-flex gap-2">
  <input type="hidden" name="page" value="librarian_members">

  <input type="text"
         name="search"
         class="form-control"
         placeholder="Search by name, email, or phone…"
         value="<?= e($search) ?>">

  <button class="btn btn-success">Search</button>
</form>

<?php if($memberDetail): ?>

<div class="card border-0 shadow-sm mb-4 border-start border-success border-3">

  <div class="card-header bg-white fw-semibold">
    <i class="bi bi-person me-2"></i>
    <?= e($memberDetail['name']) ?> — Full History
  </div>

  <div class="card-body">

    <div class="row g-3">

      <!-- Loan history -->
      <div class="col-md-6">

        <h6 class="fw-semibold">
          Loans (<?= count($memberLoans) ?>)
        </h6>

        <div class="table-responsive">
          <table class="table table-sm small">

            <thead class="table-light">
              <tr>
                <th>Book</th>
                <th>Status</th>
                <th>Due</th>
              </tr>
            </thead>

            <tbody>

            <?php foreach($memberLoans as $l): ?>
            <tr>

              <td><?= e($l['book_title']) ?></td>

              <td>
                <span class="badge bg-<?= [
                  'active'=>'success',
                  'returned'=>'secondary',
                  'pending'=>'warning',
                  'rejected'=>'danger'
                ][$l['status']] ?>">
                  <?= $l['status'] ?>
                </span>
              </td>

              <td><?= e($l['due_date'] ?? '—') ?></td>

            </tr>
            <?php endforeach; ?>

            </tbody>

          </table>
        </div>

      </div>

      <!-- Fine history -->
      <div class="col-md-6">

        <h6 class="fw-semibold">
          Fines (<?= count($memberFines) ?>)
        </h6>

        <div class="table-responsive">
          <table class="table table-sm small">

            <thead class="table-light">
              <tr>
                <th>Amount</th>
                <th>Reason</th>
                <th>Paid</th>
              </tr>
            </thead>

            <tbody>

            <?php foreach($memberFines as $f): ?>
            <tr>

              <td>
                ৳<?= number_format($f['amount'], 2) ?>
              </td>

              <!-- Keep long reasons compact -->
              <td>
                <?= e(mb_strimwidth($f['reason'], 0, 30, '…')) ?>
              </td>

              <td>
                <?= $f['is_paid'] ? '✅' : '❌' ?>
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

<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">

    <div class="table-responsive">

      <table class="table table-hover align-middle mb-0 small">

        <thead class="table-success">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Branch</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>

        <?php foreach($members as $m): ?>
        <tr>

          <td class="fw-semibold">
            <?= e($m['name']) ?>
          </td>

          <td><?= e($m['email']) ?></td>

          <td><?= e($m['phone'] ?? '—') ?></td>

          <td><?= e($m['branch_name'] ?? '—') ?></td>

          <td>
            <?= $m['is_active']
              ? '<span class="badge bg-success">Active</span>'
              : '<span class="badge bg-secondary">Inactive</span>' ?>
          </td>

          <td>
            <!-- Open detailed member history -->
            <a href="<?= BASE_URL ?>index.php?page=librarian_members&search=<?= urlencode($m['name']) ?>&member_id=<?= $m['id'] ?>"
               class="btn btn-sm btn-outline-success">
              <i class="bi bi-eye"></i>
            </a>
          </td>

        </tr>
        <?php endforeach; ?>

        </tbody>

      </table>

    </div>

  </div>
</div>