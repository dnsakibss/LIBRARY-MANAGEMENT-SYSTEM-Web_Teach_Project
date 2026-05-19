<?php // app/view/admin/book_edit.php ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Book</h2>
  <a href="<?= BASE_URL ?>index.php?page=admin_books" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back to Catalog
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="card border-0 shadow-sm">
      
      <div class="card-header bg-warning text-dark fw-semibold">
        <i class="bi bi-pencil me-2"></i>Editing: <?= e($book['title']) ?>
      </div>
      
      <div class="card-body p-4">
        
        <?php if (!empty($errors)): ?>
          <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger py-2 small"><?= e($err) ?></div>
          <?php endforeach; ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="row g-3">

            <div class="col-12">
              <h6 class="fw-bold border-bottom pb-2"><i class="bi bi-book me-2"></i>Book Information</h6>
            </div>

            <div class="col-md-8">
              <label class="form-label fw-semibold">Title *</label>
              <input type="text" name="title" class="form-control" value="<?= e($book['title']) ?>" required>
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">ISBN *</label>
              <input type="text" name="isbn" class="form-control" value="<?= e($book['isbn']) ?>" required>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Author *</label>
              <input type="text" name="author" class="form-control" value="<?= e($book['author']) ?>" required>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Genre</label>
              <select name="genre_id" class="form-select">
                <option value="">-- Select Genre --</option>
                <?php foreach ($genres as $g): ?>
                  <?php
                  // Basic match verification to apply the 'selected' attribute tag
                  $isSelected = "";
                  if ($book['genre_id'] == $g['id']) {
                      $isSelected = "selected";
                  }
                  ?>
                  <option value="<?= $g['id'] ?>" <?= $isSelected ?>><?= e($g['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <div class="col-md-5">
              <label class="form-label fw-semibold">Publisher</label>
              <?php
              // Handle default value safely without using short-hand null coalescing assignment operators
              $publisherVal = "";
              if (isset($book['publisher'])) {
                  $publisherVal = $book['publisher'];
              }
              ?>
              <input type="text" name="publisher" class="form-control" value="<?= e($publisherVal) ?>">
            </div>
            
            <div class="col-md-3">
              <label class="form-label fw-semibold">Published Year</label>
              <?php
              $yearVal = "";
              if (isset($book['published_year'])) {
                  $yearVal = $book['published_year'];
              }
              ?>
              <input type="number" name="published_year" class="form-control" value="<?= e($yearVal) ?>" min="1000" max="<?= date('Y') ?>">
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">Cover Image</label>
              <?php 
              // Image Check: Show thumbnail preview if cover image string exists in database
              if (!empty($book['cover_image_path'])): 
              ?>
                <div class="mb-1">
                  <img src="<?= BASE_URL ?>public/<?= e($book['cover_image_path']) ?>" height="50" class="rounded border">
                  <small class="text-muted ms-2">Current</small>
                </div>
              <?php endif; ?>
              <input type="file" name="cover_image" class="form-control" accept="image/*">
            </div>
            
            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <?php
              $descVal = "";
              if (isset($book['description'])) {
                  $descVal = $book['description'];
              }
              ?>
              <textarea name="description" class="form-control" rows="3"><?= e($descVal) ?></textarea>
            </div>


            <div class="col-12 mt-2">
              <h6 class="fw-bold border-bottom pb-2">
                <i class="bi bi-building me-2 text-dark"></i>Copies Per Branch
                <small class="text-muted fw-normal ms-2">(0 = remove from branch)</small>
              </h6>
            </div>

            <?php foreach ($branches as $branch): ?>
              <?php 
              // Skip current loop iteration if library branch status is flagged inactive
              if ($branch['is_active'] == false) {
                  continue;