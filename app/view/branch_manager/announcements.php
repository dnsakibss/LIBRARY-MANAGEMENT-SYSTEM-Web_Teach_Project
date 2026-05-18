<?php // app/view/branch_manager/announcements.php ?>
<h2 class="mb-4"><i class="bi bi-megaphone me-2 text-danger"></i>Announcements</h2>
<div class="row g-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">Post Announcement</h6>
        <form method="POST">
          <div class="mb-2">
            <label class="form-label small fw-semibold">Scope</label>
            <select name="scope" class="form-select form-select-sm" id="scopeSelect" onchange="toggleBranch()">
              <option value="platform">Platform-wide</option>
              <option value="branch">Specific Branch</option>
            </select>
          </div>
          <div class="mb-2" id="branchDiv" style="display:none">
            <label class="form-label small fw-semibold">Branch</label>
            <select name="branch_id" class="form-select form-select-sm">
              <?php foreach ($branches as $b): ?>
              <option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold">Title</label>
            <input type="text" name="title" class="form-control form-control-sm" required>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold">Body</label>
            <textarea name="body" class="form-control form-control-sm" rows="4" required></textarea>
          </div>
          <button class="btn btn-danger btn-sm w-100"><i class="bi bi-send me-1"></i>Post</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <?php foreach ($announcements as $a): ?>
    <div class="card border-0 shadow-sm mb-2">
      <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-start">
          <strong class="small"><?= e($a['title']) ?></strong>
          <span class="badge bg-<?= $a['branch_id'] ? 'secondary' : 'primary' ?> ms-2">
            <?= $a['branch_id'] ? e($a['branch_name'] ?? 'Branch') : 'Platform-wide' ?>
          </span>
        </div>
        <p class="small text-secondary mb-1"><?= nl2br(e($a['body'])) ?></p>
        <small class="text-muted">By <?= e($a['author_name']) ?> — <?= e(substr($a['published_at'], 0, 10)) ?></small>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<script>
function toggleBranch() {
  document.getElementById('branchDiv').style.display =
    document.getElementById('scopeSelect').value === 'branch' ? 'block' : 'none';
}
</script>
