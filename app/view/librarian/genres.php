<?php // app/view/librarian/genres.php ?>

<!-- Page title -->
<h2 class="mb-4">
  <i class="bi bi-tags me-2 text-success"></i>
  Manage Genres
</h2>

<div class="row g-4">

  <!-- Add Genre Section -->
  <div class="col-md-4">

    <div class="card border-0 shadow-sm">
      <div class="card-body">

        <h6 class="fw-semibold mb-3">Add Genre</h6>

        <!-- Simple form to create a new genre -->
        <form method="POST">

          <input type="hidden" name="action" value="add">

          <div class="input-group">

            <input type="text"
                   name="name"
                   class="form-control"
                   placeholder="Genre name"
                   required>

            <button class="btn btn-success">Add</button>

          </div>

        </form>

      </div>
    </div>

  </div>

  <!-- Existing Genres List -->
  <div class="col-md-8">

    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0 small">

          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>

          <!-- Loop through all genres -->
          <?php foreach($genres as $g): ?>

          <tr>

            <td>

              <!-- Inline rename form -->
              <form method="POST" class="d-inline-flex gap-2 align-items-center">

                <input type="hidden" name="action" value="rename">
                <input type="hidden" name="id" value="<?= $g['id'] ?>">

                <input type="text"
                       name="name"
                       class="form-control form-control-sm"
                       value="<?= e($g['name']) ?>"
                       style="width:180px">

                <button class="btn btn-sm btn-outline-warning">
                  Rename
                </button>

              </form>

            </td>

            <td>

              <!-- Delete action -->
              <form method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Delete genre?')">

                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $g['id'] ?>">

                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>

              </form>

            </td>

          </tr>

          <?php endforeach; ?>

          </tbody>

        </table>

      </div>
    </div>

  </div>

</div>