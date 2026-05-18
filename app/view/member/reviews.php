<?php // app/view/member/reviews.php ?>
<h2 class="mb-4"><i class="bi bi-star me-2 text-warning"></i>My Reviews</h2>
<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <?php if (empty($reviews)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-star fs-1 d-block mb-2 opacity-25"></i>
        <p>You haven't reviewed any books yet.</p>
        <a href="<?= BASE_URL ?>index.php?page=member_books" class="btn btn-primary btn-sm">Browse Books</a>
      </div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr><th>Book</th><th>Rating</th><th>Review</th><th>Date</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php foreach ($reviews as $r): ?>
        <tr>
          <td>
            <strong><?= e($r['book_title']) ?></strong><br>
            <a href="<?= BASE_URL ?>index.php?page=member_book_detail&id=<?= $r['book_id'] ?>" class="small text-primary">View Book</a>
          </td>
          <td>
            <span class="text-warning"><?= str_repeat('★', $r['rating']) ?><?= str_repeat('☆', 5 - $r['rating']) ?></span>
            <small class="text-muted ms-1"><?= $r['rating'] ?>/5</small>
          </td>
          <td class="small text-secondary"><?= e(mb_strimwidth($r['review_text'] ?? '—', 0, 80, '…')) ?></td>
          <td class="small text-muted"><?= e(substr($r['created_at'], 0, 10)) ?></td>
          <td>
            <a href="<?= BASE_URL ?>index.php?page=member_book_detail&id=<?= $r['book_id'] ?>" class="btn btn-sm btn-outline-warning">
              <i class="bi bi-pencil me-1"></i>Edit
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer bg-white text-muted small"><?= count($reviews) ?> review(s)</div>
    <?php endif; ?>
  </div>
</div>
