<?php // app/view/librarian/stats.php ?>

<h2 class="mb-4">
  <i class="bi bi-bar-chart me-2 text-success"></i>Catalog Statistics
</h2>

<div class="row g-4">

  <!-- Most borrowed books section -->
  <div class="col-md-6">

    <div class="card border-0 shadow-sm">

      <div class="card-header bg-white fw-semibold text-success">
        Most Borrowed Books
      </div>

      <div class="card-body p-0">

        <table class="table table-hover mb-0 small">

          <thead class="table-light">
            <tr>
              <th>Title</th>
              <th>Author</th>
              <th>Borrows</th>
            </tr>
          </thead>

          <tbody>

          <?php foreach($mostBorrowed as $b): ?>
          <tr>

            <td><?= e($b['title']) ?></td>

            <td><?= e($b['author']) ?></td>

            <td>
              <span class="badge bg-success">
                <?= $b['borrow_count'] ?>
              </span>
            </td>

          </tr>
          <?php endforeach; ?>

          <?php if(empty($mostBorrowed)): ?>
          <tr>
            <td colspan="3" class="text-muted text-center py-3">
              No data yet.
            </td>
          </tr>
          <?php endif; ?>

          </tbody>

        </table>

      </div>

    </div>

  </div>

  <!-- Genre popularity overview -->
  <div class="col-md-6">

    <div class="card border-0 shadow-sm">

      <div class="card-header bg-white fw-semibold text-warning">
        Borrows by Genre
      </div>

      <div class="card-body p-0">

        <table class="table table-hover mb-0 small">

          <thead class="table-light">
            <tr>
              <th>Genre</th>
              <th>Total Borrows</th>
            </tr>
          </thead>

          <tbody>

          <?php foreach($genreStats as $g): ?>
          <tr>

            <td><?= e($g['name']) ?></td>

            <td>
              <span class="badge bg-warning text-dark">
                <?= $g['total'] ?>
              </span>
            </td>

          </tr>
          <?php endforeach; ?>

          <?php if(empty($genreStats)): ?>
          <tr>
            <td colspan="2" class="text-muted text-center py-3">
              No data yet.
            </td>
          </tr>
          <?php endif; ?>

          </tbody>

        </table>

      </div>

    </div>

  </div>

  <!-- Books that never got borrowed -->
  <div class="col-12">

    <div class="card border-0 shadow-sm">

      <div class="card-header bg-white fw-semibold text-secondary">
        Books Never Borrowed at This Branch
      </div>

      <div class="card-body p-0">

        <table class="table table-hover mb-0 small">

          <thead class="table-light">
            <tr>
              <th>Title</th>
              <th>Author</th>
            </tr>
          </thead>

          <tbody>

          <?php foreach($neverBorrowed as $b): ?>
          <tr>

            <td><?= e($b['title']) ?></td>

            <td><?= e($b['author']) ?></td>

          </tr>
          <?php endforeach; ?>

          <?php if(empty($neverBorrowed)): ?>
          <tr>
            <td colspan="2" class="text-muted text-center py-3">
              All books have been borrowed at least once.
            </td>
          </tr>
          <?php endif; ?>

          </tbody>

        </table>

      </div>

    </div>

  </div>

</div>