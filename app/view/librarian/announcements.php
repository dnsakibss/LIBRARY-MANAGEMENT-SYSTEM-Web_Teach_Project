<?php // app/view/librarian/announcements.php ?>
<h2 class="mb-4"><i class="bi bi-megaphone me-2 text-success"></i>Branch Announcements</h2>
<div class="row g-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">Post Announcement</h6>
        <form method="POST">
          <div class="mb-2">
            <label class="form-label small fw-semibold">Title</label>
            <input type="text" name="title" class="form-control form-control-sm" required>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold">Body</label>
            <textarea name="body" class="form-control form-control-sm" rows="4" required></textarea>
          </div>
          <button class="btn btn-success btn-sm w-100"><i class="bi bi-send me-1"></i>Post</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <?php if (empty($announcements)): ?>
      <p class="text-muted text-center py-5">No announcements yet.</p>
    <?php else: foreach ($announcements as $a): ?>
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <h6 class="fw-bold mb-1"><?= e($a['title']) ?></h6>
          <span class="badge bg-<?= $a['branch_id'] ? 'secondary' : 'primary' ?>">
            <?= $a['branch_id'] ? e($a['branch_name'] ?? 'Branch') : 'Platform-wide' ?>
          </span>
        </div>
        <p class="small text-secondary mb-1"><?= nl2br(e($a['body'])) ?></p>
        <small class="text-muted">By <?= e($a['author_name']) ?> — <?= e(substr($a['published_at'], 0, 10)) ?></small>
      </div>
    </div>
    <?php endforeach; endif; ?>
  </div>
</div>
