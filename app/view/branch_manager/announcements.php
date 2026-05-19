<?php // app/view/branch_manager/announcements.php ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-megaphone me-2 text-danger"></i>Announcements</h2>
  <span class="badge bg-secondary fs-6" title="Total announcements posted">
    <?= count($announcements) ?> announcement(s)
  </span>
</div>

<div class="row g-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold text-danger">
        <i class="bi bi-pencil-square me-2"></i>Post Announcement
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="mb-2">
            <label class="form-label small fw-semibold">Scope</label>
            <select name="scope" class="form-select form-select-sm" id="scopeSelect" onchange="toggleBranch()" title="Select announcement scope">
              <option value="platform">Platform-wide</option>
              <option value="branch">Specific Branch</option>
            </select>
          </div>
          <div class="mb-2" id="branchDiv" style="display:none">
            <label class="form-label small fw-semibold">Branch</label>
            <select name="branch_id" class="form-select form-select-sm" title="Select target branch">
              <?php foreach ($branches as $b): ?>
              <option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold">Title</label>
            <input type="text" name="title" class="form-control form-control-sm" required placeholder="Enter announcement title" title="Announcement title">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold">Body</label>
            <textarea name="body" class="form-control form-control-sm" rows="4" required placeholder="Write your announcement here..." title="Announcement body"></textarea>
          </div>
          <button class="btn btn-danger btn-sm w-100" title="Post this announcement">
            <i class="bi bi-send me-1"></i>Post
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <?php if (empty($announcements)): ?>
      <div class="alert alert-info text-center">
        <i class="bi bi-inbox me-2"></i>No announcements yet. Post one to get started.
      </div>
    <?php else: ?>
    <?php foreach ($announcements as $a): ?>
    <div class="card border-0 shadow-sm mb-2">
      <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-start">
          <strong class="small"><?= e($a['title']) ?></strong>
          <span class="badge bg-<?= $a['branch_id'] ? 'secondary' : 'primary' ?> ms-2"
            title="<?= $a['branch_id'] ? 'Branch-specific announcement' : 'Visible to all branches' ?>">
            <?= $a['branch_id'] ? e($a['branch_name'] ?? 'Branch') : 'Platform-wide' ?>
          </span>
        </div>
        <p class="small text-secondary mb-1"><?= nl2br(e($a['body'])) ?></p>
        <small class="text-muted">
          <i class="bi bi-person me-1"></i>By <?= e($a['author_name']) ?>
          <span class="mx-1">—</span>
          <i class="bi bi-calendar me-1"></i><?= e(substr($a['published_at'], 0, 10)) ?>
        </small>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
function toggleBranch() {
  document.getElementById('branchDiv').style.display =
    document.getElementById('scopeSelect').value === 'branch' ? 'block' : 'none';
}
</script>