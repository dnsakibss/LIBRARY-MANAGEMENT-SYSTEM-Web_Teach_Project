<?php // app/view/member/profile.php ?>
<h2 class="mb-4"><i class="bi bi-person-circle me-2 text-primary"></i>My Profile</h2>
<div class="row g-4">
  <div class="col-md-3 text-center">
    <?php if ($user['profile_pic']): ?>
      <img src="<?= BASE_URL ?>public/<?= e($user['profile_pic']) ?>" class="rounded-circle shadow" width="120" height="120" style="object-fit:cover">
    <?php else: ?>
      <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center shadow" style="width:120px;height:120px">
        <span class="text-white fw-bold fs-2"><?= strtoupper(substr($user['name'],0,2)) ?></span>
      </div>
    <?php endif; ?>
    <p class="fw-bold mt-2 mb-0"><?= e($user['name']) ?></p>
    <span class="badge bg-primary"><?= e($user['role']) ?></span>
  </div>

  <div class="col-md-9">
    <!-- Update Profile -->
    <?php if (!empty($errors)): foreach ($errors as $err): ?>
      <div class="alert alert-danger py-2 small"><?= e($err) ?></div>
    <?php endforeach; endif; ?>

    <ul class="nav nav-tabs mb-3">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-info">Profile Info</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-password">Change Password</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="tab-info">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="update_profile">
          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Profile Picture</label>
            <input type="file" name="profile_pic" class="form-control" accept="image/*">
          </div>
          <button class="btn btn-primary">Save Changes</button>
        </form>
      </div>
      <div class="tab-pane fade" id="tab-password">
        <form method="POST">
          <input type="hidden" name="action" value="change_password">
          <div class="mb-3">
            <label class="form-label fw-semibold">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">New Password</label>
            <input type="password" name="new_password" class="form-control" minlength="6" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
          </div>
          <button class="btn btn-warning">Change Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
