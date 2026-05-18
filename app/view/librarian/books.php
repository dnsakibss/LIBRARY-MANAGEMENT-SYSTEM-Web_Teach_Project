<?php // app/view/librarian/books.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-journals me-2 text-success"></i>Book Catalog</h2>
  <div class="d-flex gap-2">
    <a href="<?= BASE_URL ?>index.php?page=librarian_genres" class="btn btn-outline-secondary btn-sm"><i class="bi bi-tags me-1"></i>Genres</a>
    <a href="<?= BASE_URL ?>index.php?page=librarian_book_add" class="btn btn-success"><i class="bi bi-plus me-1"></i>Add Book</a>
  </div>
</div>
<form method="GET" action="<?= BASE_URL ?>index.php" class="mb-3 d-flex gap-2">
  <input type="hidden" name="page" value="librarian_books">
  <input type="text" name="search" class="form-control" placeholder="Search books…" value="<?= e($search) ?>">
  <button class="btn btn-success">Search</button>
  <?php if($search): ?><a href="<?= BASE_URL ?>index.php?page=librarian_books" class="btn btn-outline-secondary">Clear</a><?php endif; ?>
</form>
<div class="card border-0 shadow-sm"><div class="card-body p-0">
  <div class="table-responsive"><table class="table table-hover align-middle mb-0 small">
    <thead class="table-success"><tr><th>#</th><th>Title</th><th>Author</th><th>ISBN</th><th>Genre</th><th>Year</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($books as $i=>$b): ?>
    <tr>
      <td class="text-muted"><?= $i+1 ?></td>
      <td class="fw-semibold"><?= e($b['title']) ?></td>
      <td><?= e($b['author']) ?></td>
      <td class="font-monospace small"><?= e($b['isbn']) ?></td>
      <td><?= e($b['genre_name'] ?? '—') ?></td>
      <td><?= e($b['published_year'] ?? '—') ?></td>
      <td>
        <a href="<?= BASE_URL ?>index.php?page=librarian_book_edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
</div></div>
