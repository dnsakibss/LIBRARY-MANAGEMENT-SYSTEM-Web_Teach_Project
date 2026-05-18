<?php // app/view/member/book_detail.php
$memberId = $_SESSION['user_id'];
$myReview = null;
foreach ($reviews as $r) { if ($r['member_id'] == $memberId) { $myReview = $r; break; } }
?>
<div class="mb-3">
  <a href="<?= BASE_URL ?>index.php?page=member_books" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back to Books
  </a>
</div>

<div class="row g-4">
  <!-- Cover + Reading List -->
  <div class="col-lg-3 col-md-4">
    <?php if ($book['cover_image_path']): ?>
      <img src="<?= BASE_URL ?>public/<?= e($book['cover_image_path']) ?>"
           class="img-fluid rounded shadow w-100" style="max-height:320px;object-fit:cover">
    <?php else: ?>
      <div class="bg-light rounded d-flex align-items-center justify-content-center shadow"
           style="height:280px"><i class="bi bi-book fs-1 text-muted opacity-25"></i></div>
    <?php endif; ?>
    <button id="rlBtn" class="btn btn-<?= $inList ? 'warning' : 'outline-secondary' ?> w-100 mt-2"
      onclick="toggleReadingList(<?= $book['id'] ?>)">
      <i class="bi bi-bookmark<?= $inList ? '-fill' : '' ?> me-1"></i>
      <span id="rlText"><?= $inList ? 'In Reading List' : 'Add to Reading List' ?></span>
    </button>
  </div>

  <!-- Details -->
  <div class="col-lg-5 col-md-8">
    <h2 class="fw-bold mb-1"><?= e($book['title']) ?></h2>
    <p class="text-muted fs-5 mb-2">by <strong><?= e($book['author']) ?></strong></p>
    <div class="mb-3">
      <span class="badge bg-primary me-1"><?= e($book['genre_name'] ?? 'Unknown') ?></span>
      <span class="text-warning fs-6"><?= str_repeat('★', (int)round($avgRating)) ?><?= str_repeat('☆', 5 - (int)round($avgRating)) ?></span>
      <small class="text-muted ms-1"><?= $avgRating ?>/5 (<?= count($reviews) ?> review<?= count($reviews) != 1 ? 's' : '' ?>)</small>
    </div>
    <dl class="row small mb-3">
      <dt class="col-4 text-muted">ISBN</dt>       <dd class="col-8 font-monospace"><?= e($book['isbn']) ?></dd>
      <dt class="col-4 text-muted">Publisher</dt>  <dd class="col-8"><?= e($book['publisher'] ?? '—') ?></dd>
      <dt class="col-4 text-muted">Year</dt>       <dd class="col-8"><?= e($book['published_year'] ?? '—') ?></dd>
    </dl>
    <?php if ($book['description']): ?>
      <p class="text-secondary"><?= nl2br(e($book['description'])) ?></p>
    <?php endif; ?>
    <h6 class="fw-semibold mt-3"><i class="bi bi-building me-1"></i>Branch Availability</h6>
    <div id="availabilityTable"><small class="text-muted"><i class="bi bi-hourglass-split me-1"></i>Loading…</small></div>
  </div>

  <!-- Borrow Card -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold"><i class="bi bi-send me-2 text-primary"></i>Borrow This Book</div>
      <div class="card-body">
        <!-- Borrow Limit -->
        <div class="mb-3 p-2 rounded <?= $limitReached ? 'bg-danger bg-opacity-10 border border-danger' : 'bg-success bg-opacity-10 border border-success' ?>">
          <div class="d-flex justify-content-between align-items-center">
            <small class="fw-semibold <?= $limitReached ? 'text-danger' : 'text-success' ?>">
              <i class="bi bi-<?= $limitReached ? 'x-circle' : 'check-circle' ?> me-1"></i>Borrow Limit
            </small>
            <span class="badge bg-<?= $limitReached ? 'danger' : 'success' ?>"><?= $currentActive ?> / <?= $maxBooks ?> books</span>
          </div>
          <?php if ($limitReached): ?>
            <small class="text-danger d-block mt-1">Limit reached. Return a book to borrow more.</small>
          <?php else: ?>
            <small class="text-success d-block mt-1">You can borrow <?= $remaining ?> more book<?= $remaining != 1 ? 's' : '' ?>.</small>
          <?php endif; ?>
        </div>

        <?php if ($limitReached): ?>
          <div class="text-center py-2">
            <i class="bi bi-lock fs-2 text-danger opacity-50"></i>
            <p class="text-muted small mt-1">Return a book first.</p>
            <a href="<?= BASE_URL ?>index.php?page=member_my_loans" class="btn btn-outline-danger btn-sm">
              <i class="bi bi-arrow-return-left me-1"></i>View My Loans
            </a>
          </div>
        <?php else: ?>
          <form method="POST" action="<?= BASE_URL ?>index.php?page=member_borrow_request">
            <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
            <div class="mb-3">
              <label class="form-label small fw-semibold">Select Branch</label>
              <select name="branch_id" class="form-select form-select-sm" required>
                <option value="">-- Choose Branch --</option>
                <?php foreach ($inventory as $inv): ?>
                <option value="<?= $inv['branch_id'] ?>"
                  <?= $inv['available_copies'] < 1 ? 'disabled' : '' ?>
                  <?= (int)$inv['branch_id'] === (int)$_SESSION['branch_id'] ? 'selected' : '' ?>>
                  <?= e($inv['branch_name']) ?><?= (int)$inv['branch_id'] === (int)$_SESSION['branch_id'] ? ' ⭐ Your Branch' : '' ?>
                  (<?= $inv['available_copies'] ?>/<?= $inv['total_copies'] ?> available)
                </option>
                <?php endforeach; ?>
              </select>
              <div class="form-text"><i class="bi bi-info-circle me-1"></i>Your branch is pre-selected. Requests go to that branch's librarian.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-send me-1"></i>Submit Borrow Request
            </button>
          </form>
          <p class="text-muted small mt-2 mb-0">If no copies available, you'll be added to the waitlist.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Reviews -->
<div class="row g-4 mt-2">
  <div class="col-lg-8">
    <h5 class="fw-semibold"><i class="bi bi-star me-2 text-warning"></i>Reviews</h5>
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-header bg-white small fw-semibold">
        <?= $myReview ? '<i class="bi bi-pencil me-1"></i>Edit Your Review' : '<i class="bi bi-plus-circle me-1"></i>Write a Review' ?>
      </div>
      <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>index.php?page=member_book_detail&id=<?= $book['id'] ?>">
          <div class="row g-2 align-items-end">
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Rating</label>
              <select name="rating" class="form-select form-select-sm">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?= $i ?>" <?= ($myReview && $myReview['rating'] == $i) ? 'selected' : '' ?>><?= $i ?> <?= str_repeat('★',$i) ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="col-md-9">
              <label class="form-label small fw-semibold">Your Thoughts</label>
              <textarea name="review_text" class="form-control form-control-sm" rows="2" placeholder="Share your opinion…"><?= $myReview ? e($myReview['review_text']) : '' ?></textarea>
            </div>
          </div>
          <div class="d-flex gap-2 mt-2">
            <button type="submit" name="submit_review" class="btn btn-sm btn-success"><i class="bi bi-check me-1"></i>Save Review</button>
            <?php if ($myReview): ?>
            <button type="submit" name="delete_review" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete your review?')"><i class="bi bi-trash me-1"></i>Delete</button>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
    <?php $otherReviews = array_filter($reviews, fn($rv) => $rv['member_id'] != $memberId); ?>
    <?php if (empty($otherReviews)): ?>
      <p class="text-muted small">No other reviews yet.</p>
    <?php else: foreach ($otherReviews as $rv): ?>
    <div class="card border-0 shadow-sm mb-2">
      <div class="card-body py-2 px-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <strong class="small"><?= e($rv['member_name']) ?></strong>
          <span class="text-warning small"><?= str_repeat('★',$rv['rating']) ?><?= str_repeat('☆',5-$rv['rating']) ?></span>
        </div>
        <p class="mb-0 small text-secondary"><?= nl2br(e($rv['review_text'] ?? '')) ?></p>
      </div>
    </div>
    <?php endforeach; endif; ?>
  </div>
</div>

<script>
function toggleReadingList(bookId) {
  const btn = document.getElementById('rlBtn');
  const text = document.getElementById('rlText');
  const xhr  = new XMLHttpRequest();
  xhr.open('POST', '<?= BASE_URL ?>index.php?page=ajax_reading_list');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onload = function() {
    const data = JSON.parse(xhr.responseText);
    if (data.action === 'added') {
      btn.className = 'btn btn-warning w-100 mt-2';
      text.textContent = 'In Reading List';
    } else {
      btn.className = 'btn btn-outline-secondary w-100 mt-2';
      text.textContent = 'Add to Reading List';
    }
  };
  xhr.send('book_id=' + bookId);
}
(function() {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', '<?= BASE_URL ?>index.php?page=ajax_book_availability&book_id=<?= $book['id'] ?>');
  xhr.onload = function() {
    const data = JSON.parse(xhr.responseText);
    if (!data.inventory || data.inventory.length === 0) {
      document.getElementById('availabilityTable').innerHTML = '<small class="text-muted">Not available at any branch.</small>';
      return;
    }
    let html = '<table class="table table-sm table-bordered small mb-0"><thead class="table-light"><tr><th>Branch</th><th>City</th><th>Available</th><th>Total</th></tr></thead><tbody>';
    data.inventory.forEach(function(row) {
      const badge = row.available_copies > 0 ? `<span class="badge bg-success">${row.available_copies}</span>` : `<span class="badge bg-danger">0</span>`;
      html += `<tr><td>${row.branch_name}</td><td>${row.city||'—'}</td><td>${badge}</td><td>${row.total_copies}</td></tr>`;
    });
    html += '</tbody></table>';
    document.getElementById('availabilityTable').innerHTML = html;
  };
  xhr.send();
})();
</script>
