<?php // app/view/admin/books.php ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-journals me-2"></i>Global Book Catalog</h2>
  <a href="<?= BASE_URL ?>index.php?page=admin_book_add" class="btn btn-dark">
    <i class="bi bi-plus-circle me-1"></i>Add New Book
  </a>
</div>

<form method="GET" action="<?= BASE_URL ?>index.php" class="mb-3 d-flex gap-2">
  <input type="hidden" name="page" value="admin_books">
  <input type="text" name="search" class="form-control" placeholder="Search by title, author or ISBN…" value="<?= e($search) ?>">
  <button class="btn btn-dark"><i class="bi bi-search me-1"></i>Search</button>
  
  <?php 
  // Conditional statement to display clear filter link button if search parameter is set
  if ($search != ""): 
  ?>
    <a href="<?= BASE_URL ?>index.php?page=admin_books" class="btn btn-outline-secondary">
      <i class="bi bi-x-circle me-1"></i>Clear
    </a>
  <?php endif; ?>
</form>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Cover</th>
            <th>Title</th>
            <th>Author</th>
            <th>ISBN</th>
            <th>Genre</th>
            <th>Year</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
        
        <?php 
        // EMPTY STATE EXPLICIT VERIFICATION: Displays fallback placeholder row if the array data holds no counts
        if (empty($books)): 
        ?>
          <tr>
            <td colspan="8" class="text-muted text-center py-5">
              <i class="bi bi-journals fs-1 d-block mb-2 opacity-25"></i>
              <?php
              // Handle alternative custom wording outputs without utilizing compact embedded statements
              if ($search != "") {
                  echo "No books found for \"" . e($search) . "\".";
              } else {
                  echo "No books found.";
              }
              ?>
            </td>
          </tr>
        <?php 
        else: 
            // CORE LOOP: Process through array parameters rows sequentially 
            foreach ($books as $i => $b): 
        ?>
        <tr>
          <td class="text-muted"><?= $i + 1 ?></td>
          
          <td>
            <?php if ($b['cover_image_path'] != null): ?>
              <img src="<?= BASE_URL ?>public/<?= e($b['cover_image_path']) ?>" height="45" style="object-fit:cover;border-radius:4px;box-shadow:0 1px 4px rgba(0,0,0,.15)">
            <?php else: ?>
              <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:32px;height:45px">
                <i class="bi bi-image text-muted small"></i>
              </div>
            <?php endif; ?>
          </td>
          
          <td class="fw-semibold"><?= e($b['title']) ?></td>
          <td><?= e($b['author']) ?></td>
          <td class="font-monospace"><?= e($b['isbn']) ?></td>
          
          <td>
            <?php
            $genreName = "—";
            if (isset($b['genre_name'])) {
                $genreName = $b['genre_name'];
            }
            ?>
            <span class="badge bg-secondary"><?= e($genreName) ?></span>
          </td>
          
          <td>
            <?php
            $publishedYear = "—";
            if (isset($b['published_year'])) {
                $publishedYear = $b['published_year'];
            }
            echo e($publishedYear);
            ?>
          </td>
          
          <td class="text-center">
            <div class="d-flex gap-1 justify-content-center">
              
              <a href="<?= BASE_URL ?>index.php?page=admin_book_edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit Book">
                <i class="bi bi-pencil"></i>
              </a>
              
              <form method="POST" class="d-inline" onsubmit="return confirm('Permanently delete \'<?= e(addslashes($b['title'])) ?>\'? This cannot be undone.')">
                <input type="hidden" name="delete_id" value="<?= $b['id'] ?>">
                <button class="btn btn-sm btn-outline-danger" title="Delete Book">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
              
            </div>
          </td>
        </tr>
        <?php 
            endforeach; 
        endif; 
        ?>
        </tbody>
      </table>
    </div>
    
    <div class="card-footer bg-white text-muted small">
      <?= count($books) ?> book(s) in catalog
      <?php if ($search != ""): ?> 
        — filtered by "<?= e($search) ?>"
      <?php endif; ?>
    </div>
    
  </div>
</div>