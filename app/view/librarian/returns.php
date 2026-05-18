<?php // app/view/librarian/returns.php ?>

<!-- Page title -->
<h2 class="mb-4">
  <i class="bi bi-arrow-return-left me-2 text-success"></i>
  Process Returns
</h2>

<!-- Search form -->
<form method="GET"
      action="<?= BASE_URL ?>index.php"
      class="mb-4 d-flex gap-2">

  <input type="hidden"
         name="page"
         value="librarian_returns">

  <input type="text"
         name="search"
         class="form-control"
         placeholder="Search by member name or record ID…"
         value="<?= e($search) ?>">

  <button class="btn btn-success">
    Search
  </button>

  <!-- Show clear button only after search -->
  <?php if($search): ?>

    <a href="<?= BASE_URL ?>index.php?page=librarian_returns"
       class="btn btn-outline-secondary">
      Clear
    </a>

  <?php endif; ?>

</form>

<div class="card border-0 shadow-sm">

  <div class="card-body p-0">

    <!-- Empty state -->
    <?php if(empty($loans)): ?>

      <p class="text-muted text-center py-5">
        No active loans found.
      </p>

    <?php else: ?>

    <div class="table-responsive">

      <table class="table table-hover align-middle mb-0 small">

        <thead class="table-success">
          <tr>
            <th>ID</th>
            <th>Member</th>
            <th>Book</th>
            <th>Borrowed</th>
            <th>Due</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>

        <?php foreach($loans as $l):

          // Check if loan is overdue
          $overdue = $l['due_date'] < date('Y-m-d');

        ?>

        <tr class="<?= $overdue ? 'table-danger' : '' ?>">

          <td>
            #<?= $l['id'] ?>
          </td>

          <td>
            <?= e($l['member_name']) ?>
          </td>

          <td>
            <?= e($l['book_title']) ?>
          </td>

          <td>
            <?= e($l['borrow_date']) ?>
          </td>

          <td class="<?= $overdue ? 'fw-bold' : '' ?>">

            <?= e($l['due_date']) ?>

            <!-- Overdue badge -->
            <?= $overdue
                ? '<span class="badge bg-danger">Overdue</span>'
                : '' ?>

          </td>

          <td>

            <!-- Return action -->
            <form method="POST"
                  onsubmit="return confirm('Mark as returned?')">

              <input type="hidden"
                     name="record_id"
                     value="<?= $l['id'] ?>">

              <button class="btn btn-sm btn-success">
                <i class="bi bi-check me-1"></i>
                Return
              </button>

            </form>

          </td>

        </tr>

        <?php endforeach; ?>

        </tbody>

      </table>

    </div>

    <?php endif; ?>

  </div>

</div>