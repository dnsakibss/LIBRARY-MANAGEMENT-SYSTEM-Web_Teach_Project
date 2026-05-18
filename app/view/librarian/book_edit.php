<?php // app/view/librarian/book_edit.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Book</h2>
  <a href="<?= BASE_URL ?>index.php?page=librarian_books" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-warning text-dark fw-semibold">
        <i class="bi bi-pencil me-2"></i>Editing: <?= e($book['title']) ?>
      </div>
      <div class="card-body p-4">
        <?php foreach ($errors as $err): ?>
          <div class="alert alert-danger py-2 small"><?= e($err) ?></div>
        <?php endforeach; ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="row g-3">

            <div class="col-12">
              <h6 class="fw-bold text-success border-bottom pb-2"><i class="bi bi-book me-2"></i>Book Information</h6>
            </div>

            <div class="col-md-8">
              <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" value="<?= e($book['title']) ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">ISBN <span class="text-danger">*</span></label>
              <input type="text" name="isbn" class="form-control" value="<?= e($book['isbn']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Author <span class="text-danger">*</span></label>
              <input type="text" name="author" class="form-control" value="<?= e($book['author']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Genre</label>
              <select name="genre_id" class="form-select">
                <option value="">-- Select Genre --</option>
                <?php foreach ($genres as $g): ?>
                <option value="<?= $g['id'] ?>" <?= $book['genre_id'] == $g['id'] ? 'selected' : '' ?>><?= e($g['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-5">
              <label class="form-label fw-semibold">Publisher</label>
              <input type="text" name="publisher" class="form-control" value="<?= e($book['publisher'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Published Year</label>
              <input type="number" name="published_year" class="form-control"
                     value="<?= e($book['published_year'] ?? '') ?>" min="1000" max="<?= date('Y') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Cover Image</label>
              <?php if ($book['cover_image_path']): ?>
                <div class="mb-1">
                  <img src="<?= BASE_URL ?>public/<?= e($book['cover_image_path']) ?>" height="50" class="rounded border">
                  <small class="text-muted ms-2">Current cover</small>
                </div>
              <?php endif; ?>
              <input type="file" name="cover_image" class="form-control" accept="image/*">
              <div class="form-text">Upload new to replace</div>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" class="form-control" rows="3"><?= e($book['description'] ?? '') ?></textarea>
            </div>

            <!-- Branch Inventory -->
            <div class="col-12 mt-2">
              <h6 class="fw-bold text-primary border-bottom pb-2">
                <i class="bi bi-building me-2"></i>Copies Per Branch
                <small class="text-muted fw-normal ms-2">(Update available copies per branch)</small>
              </h6>
            </div>

            <?php foreach ($branches as $branch): if (!$branch['is_active']) continue;
              $currentQty = $currentInventory[$branch['id']]['total_copies'] ?? 0;
              $availQty   = $currentInventory[$branch['id']]['available_copies'] ?? 0;
            ?>
            <div class="col-md-4">
              <div class="card border shadow-sm">
                <div class="card-body py-2 px-3">
                  <label class="form-label fw-semibold small mb-1">
                    <i class="bi bi-building me-1 text-primary"></i><?= e($branch['name']) ?>
                    <small class="text-muted">(<?= e($branch['city'] ?? '') ?>)</small>
                  </label>
                  <?php if ($currentQty > 0): ?>
                    <div class="mb-1">
                      <small class="text-muted">Current: <?= $availQty ?>/<?= $currentQty ?> available</small>
                    </div>
                  <?php endif; ?>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-stack"></i></span>
                    <input type="number"
                           name="inv_copies[<?= $branch['id'] ?>]"
                           class="form-control"
                           value="<?= $currentQty ?>"
                           min="0" max="999"
                           placeholder="Total copies">
                  </div>
                  <small class="text-muted">Set 0 to remove from this branch</small>
                </div>
              </div>
            </div>
            <?php endforeach; ?>

            <div class="col-12 d-flex gap-2 pt-2">
              <button type="submit" class="btn btn-warning px-4">
                <i class="bi bi-check-circle me-1"></i>Save Changes
              </button>
              <a href="<?= BASE_URL ?>index.php?page=librarian_books" class="btn btn-outline-secondary">Cancel</a>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
