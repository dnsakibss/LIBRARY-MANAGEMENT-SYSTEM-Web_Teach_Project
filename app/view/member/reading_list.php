<?php // app/view/member/reading_list.php ?>
<h2 class="mb-4"><i class="bi bi-bookmark-heart me-2 text-danger"></i>My Reading List</h2>
<div class="row g-3">
  <?php if (empty($list)): ?>
    <div class="col-12 text-center py-5 text-muted">
      <i class="bi bi-bookmark fs-1 d-block mb-2 opacity-25"></i>Your reading list is empty.
      <a href="<?= BASE_URL ?>index.php?page=member_books" class="btn btn-primary mt-2">Browse Books</a>
    </div>
  <?php else: foreach ($list as $item): ?>
  <div class="col-md-4 col-lg-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-3">
        <h6 class="fw-bold"><?= e($item['title']) ?></h6>
        <p class="text-muted small mb-1"><?= e($item['author']) ?></p>
        <span class="badge bg-secondary small mb-2"><?= e($item['genre_name'] ?? '') ?></span>
      </div>
      <div class="card-footer bg-white p-2 d-flex gap-2">
        <a href="<?= BASE_URL ?>index.php?page=member_book_detail&id=<?= $item['book_id'] ?>" class="btn btn-sm btn-outline-primary flex-fill">View</a>
        <form method="POST" class="flex-fill">
          <input type="hidden" name="remove_book_id" value="<?= $item['book_id'] ?>">
          <button class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Remove?')"><i class="bi bi-trash"></i></button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; endif; ?>
</div>
