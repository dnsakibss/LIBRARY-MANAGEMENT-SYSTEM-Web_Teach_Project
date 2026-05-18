<?php // app/view/member/announcements.php ?>
<h2 class="mb-4"><i class="bi bi-megaphone me-2 text-warning"></i>Announcements</h2>
<?php if (empty($announcements)): ?>
  <div class="text-muted text-center py-5"><i class="bi bi-megaphone fs-1 d-block mb-2 opacity-25"></i>No announcements.</div>
<?php else: foreach ($announcements as $a): ?>
<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <h5 class="fw-bold mb-1"><?= e($a['title']) ?></h5>
        <p class="mb-2"><?= nl2br(e($a['body'])) ?></p>
      </div>
      <span class="badge bg-<?= $a['branch_id'] ? 'secondary' : 'primary' ?> ms-2 flex-shrink-0">
        <?= $a['branch_id'] ? e($a['branch_name']) : 'Platform-wide' ?>
      </span>
    </div>
    <small class="text-muted">Posted by <?= e($a['author_name']) ?> on <?= e(substr($a['published_at'],0,10)) ?></small>
  </div>
</div>
<?php endforeach; endif; ?>
