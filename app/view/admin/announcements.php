<?php // app/view/admin/announcements.php ?>
<h2 class="mb-4"><i class="bi bi-megaphone me-2"></i>Manage Announcements</h2>
<div class="row g-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">Post Announcement</h6>
        <form method="POST">
          <div class="mb-2">
            <label class="form-label small fw-semibold">Scope</label>
            <select name="scope" class="form-select form-select-sm" id="scopeSel" onchange="document.getElementById('bdiv').style.display=this.value==='branch'?'block':'none'">
              <option value="platform">Platform-wide</option>
              <option value="branch">Specific Branch</option>
            </select>
          </div>
          <div class="mb-2" id="bdiv" style="display:none">
            <label class="form-label small fw-semibold">Branch</label>
            <select name="branch_id" class="form-select form-select-sm">
              <?php foreach ($branches as $b): ?><option value="<?= $b['id'] ?>"><?= e($b['name']) ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="mb-2"><label class="form-label small fw-semibold">Title</label>
            <input type="text" name="title" class="form-control form-control-sm" required></div>
          <div class="mb-2"><label class="form-label small fw-semibold">Body</label>
            <textarea name="body" class="form-control form-control-sm" rows="4" required></textarea></div>
          <button class="btn btn-dark btn-sm w-100"><i class="bi bi-send me-1"></i>Post</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card border-0 shadow-sm"><div class="card-body p-0">
      <div class="table-responsive"><table class="table table-hover align-middle mb-0 small">
        <thead class="table-dark"><tr><th>Title</th><th>Scope</th><th>Author</th><th>Posted</th></tr></thead>
        <tbody>
        <?php foreach ($announcements as $a): ?>
        <tr>
          <td class="fw-semibold"><?= e($a['title']) ?></td>
          <td><span class="badge bg-<?= $a['branch_id']?'secondary':'primary' ?>"><?= $a['branch_id']?e($a['branch_name']??'Branch'):'Platform' ?></span></td>
          <td><?= e($a['author_name']) ?></td>
          <td><?= e(substr($a['published_at'],0,10)) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table></div>
    </div></div>
  </div>
</div>
