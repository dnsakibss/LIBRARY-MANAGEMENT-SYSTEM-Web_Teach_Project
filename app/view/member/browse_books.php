<?php // app/view/member/browse_books.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-search me-2 text-primary"></i>Browse Books</h2>
</div>

<!-- Live Search (AJAX) -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body py-3">
    <div class="row g-2">
      <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" id="liveSearch" class="form-control" placeholder="Live search by title, author or ISBN…" value="<?= e($search) ?>">
        </div>
        <div id="liveResults" class="list-group mt-1 shadow" style="display:none;position:absolute;z-index:999;width:400px;max-height:300px;overflow-y:auto"></div>
      </div>
      <div class="col-md-4">
        <form method="GET" action="<?= BASE_URL ?>index.php" class="d-flex gap-2">
          <input type="hidden" name="page" value="member_books">
          <select name="genre_id" class="form-select" onchange="this.form.submit()">
            <option value="">All Genres</option>
            <?php foreach ($genres as $g): ?>
            <option value="<?= $g['id'] ?>" <?= $genreId == $g['id'] ? 'selected' : '' ?>><?= e($g['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if ($search || $genreId): ?>
          <a href="<?= BASE_URL ?>index.php?page=member_books" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Books Grid -->
<div class="row g-3" id="bookGrid">
  <?php if (empty($books)): ?>
    <div class="col-12 text-center py-5 text-muted"><i class="bi bi-journals fs-1 d-block mb-2 opacity-25"></i>No books found.</div>
  <?php else: foreach ($books as $book): ?>
  <div class="col-md-4 col-lg-3">
    <div class="card border-0 shadow-sm h-100">
      <?php if ($book['cover_image_path']): ?>
        <img src="<?= BASE_URL ?>public/<?= e($book['cover_image_path']) ?>" class="card-img-top" style="height:160px;object-fit:cover">
      <?php else: ?>
        <div class="bg-light text-center py-4 text-muted" style="height:160px"><i class="bi bi-book fs-1 opacity-25"></i></div>
      <?php endif; ?>
      <div class="card-body p-3">
        <h6 class="fw-bold mb-1"><?= e($book['title']) ?></h6>
        <p class="text-muted small mb-1"><?= e($book['author']) ?></p>
        <span class="badge bg-secondary small"><?= e($book['genre_name'] ?? 'Unknown') ?></span>
      </div>
      <div class="card-footer bg-white p-2">
        <a href="<?= BASE_URL ?>index.php?page=member_book_detail&id=<?= $book['id'] ?>" class="btn btn-sm btn-primary w-100">
          <i class="bi bi-eye me-1"></i>View Details
        </a>
      </div>
    </div>
  </div>
  <?php endforeach; endif; ?>
</div>

<script>
// AJAX live search using XMLHttpRequest (as required by spec)
(function(){
  const input   = document.getElementById('liveSearch');
  const results = document.getElementById('liveResults');
  let timer;
  input.addEventListener('input', function(){
    clearTimeout(timer);
    const q = this.value.trim();
    if (q.length < 2) { results.style.display='none'; return; }
    timer = setTimeout(function(){
      const xhr = new XMLHttpRequest();
      xhr.open('GET', '<?= BASE_URL ?>index.php?page=ajax_search_books&q=' + encodeURIComponent(q));
      xhr.onload = function(){
        if (xhr.status === 200) {
          const data = JSON.parse(xhr.responseText);
          if (data.books && data.books.length) {
            results.innerHTML = data.books.slice(0,8).map(b =>
              `<a href="<?= BASE_URL ?>index.php?page=member_book_detail&id=${b.id}" class="list-group-item list-group-item-action">
                <strong>${b.title}</strong> <small class="text-muted">by ${b.author}</small>
               </a>`
            ).join('');
            results.style.display = 'block';
          } else {
            results.innerHTML = '<div class="list-group-item text-muted">No results.</div>';
            results.style.display = 'block';
          }
        }
      };
      xhr.send();
    }, 300);
  });
  document.addEventListener('click', function(e){ if (!input.contains(e.target)) results.style.display='none'; });
})();
</script>
