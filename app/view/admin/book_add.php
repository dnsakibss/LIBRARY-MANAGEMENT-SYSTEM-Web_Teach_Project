<?php // app/view/admin/book_add.php ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Book</h2>
  <a href="<?= BASE_URL ?>index.php?page=admin_books" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back to Catalog
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="card border-0 shadow-sm">
      
      <div class="card-header bg-dark text-white fw-semibold">
        <i class="bi bi-book me-2"></i>Book Information
      </div>
      
      <div class="card-body p-4">
        
        <?php if (!empty($errors)): ?>
          <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger py-2 small">
              <i class="bi bi-exclamation-circle me-1"></i><?= e($err) ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="row g-3">

            <div class="col-md-8">
              <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
              <?php 
              // Basic form data memory backup logic to keep the typed string if submission fails
              $titleValue = "";
              if (isset($old['title'])) {
                  $titleValue = $old['title'];
              }
              ?>
              <input type="text" name="title" class="form-control" value="<?= e($titleValue) ?>" required placeholder="Book title">
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">ISBN <span class="text-danger">*</span></label>
              <?php 
              $isbnValue = "";
              if (isset($old['isbn'])) {
                  $isbnValue = $old['isbn'];
              }
              ?>
              <input type="text" name="isbn" class="form-control" value="<?= e($isbnValue) ?>" required placeholder="978-...">
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Author <span class="text-danger">*</span></label>
              <?php 
              $authorValue = "";
              if (isset($old['author'])) {
                  $authorValue = $old['author'];
              }
              ?>
              <input type="text" name="author" class="form-control" value="<?= e($authorValue) ?>" required>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Genre</label>
              <select name="genre_id" class="form-select">
                <option value="">-- Select Genre --</option>
                <?php foreach ($genres as $g): ?>
                  <?php 
                  // Checks if this options matches what was selected before submission
                  $genreSelected = "";
                  if (isset($old['genre_id'])) {
                      if ($old['genre_id'] == $g['id']) {
                          $genreSelected = "selected";
                      }
                  }
                  ?>
                  <option value="<?= $g['id'] ?>" <?= $genreSelected ?>><?= e($g['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <div class="col-md-5">
              <label class="form-label fw-semibold">Publisher</label>
              <?php 
              $publisherValue = "";
              if (isset($old['publisher'])) {
                  $publisherValue = $old['publisher'];
              }
              ?>
              <input type="text" name="publisher" class="form-control" value="<?= e($publisherValue) ?>">
            </div>
            
            <div class="col-md-3">
              <label class="form-label fw-semibold">Published Year</label>
              <?php 
              // Set to the current year as default if no value exists
              $yearValue = date('Y');
              if (isset($old['published_year'])) {
                  $yearValue = $old['published_year'];
              }
              ?>
              <input type="number" name="published_year" class="form-control" value="<?= e($yearValue) ?>" min="1000" max="<?= date('Y') ?>">
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">Cover Image</label>
              <input type="file" name="cover_image" class="form-control" accept="image/*">
            </div>
            
            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <?php 
              $descriptionValue = "";
              if (isset($old['description'])) {
                  $descriptionValue = $old['description'];
              }
              ?>
              <textarea name="description" class="form-control" rows="3" placeholder="Brief description..."><?= e($descriptionValue) ?></textarea>
            </div>

            <div class="col-12 mt-2">
              <h6 class="fw-bold border-bottom pb-2">
                <i class="bi bi-building me-2 text-dark"></i>Set Copies Per Branch
                <span class="text-danger">*</span>
                <small class="text-muted fw-normal ms-2">(Set 0 to skip a branch)</small>
              </h6>
            </div>

            <?php foreach ($branches as $branch): ?>
              <?php 
              // Skip loading closed or inactive library locations inside selection rows
              if ($branch['is_active'] == false) {
                  continue;
              }
              
              // Extract previous input copies if the validation phase returned an error
              $copiesCount = 0;
              if (isset($old['inv_copies'][$branch['id']])) {
                  $copiesCount = (int)$old['inv_copies'][$branch['id']];
              }
              ?>
              <div class="col-md-4">
                <div class="card border shadow-sm">
                  <div class="card-body py-2 px-3">
                    
                    <label class="form-label fw-semibold small mb-1">
                      <i class="bi bi-building me-1 text-dark"></i><?= e($branch['name']) ?>
                      <?php if (isset($branch['city'])): ?>
                        <small class="text-muted">(<?= e($branch['city']) ?>)</small>
                      <?php endif; ?>
                    </label>
                    
                    <div class="input-group input-group-sm">
                      <span class="input-group-text"><i class="bi bi-stack"></i></span>
                      <input type="number" name="inv_copies[<?= $branch['id'] ?>]" class="form-control" value="<?= $copiesCount ?>" min="0" max="999" placeholder="Copies">
                    </div>
                    
                    <small class="text-muted">Number of copies at this branch</small>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

            <div class="col-12 d-flex gap-2 pt-2">
              <button type="submit" class="btn btn-dark px-4">
                <i class="bi bi-check-circle me-1"></i>Add Book
              </button>
              <a href="<?= BASE_URL ?>index.php?page=admin_books" class="btn btn-outline-secondary">Cancel</a>
            </div>

          </div>
        </form>
        
      </div>
    </div>
  </div>
</div>