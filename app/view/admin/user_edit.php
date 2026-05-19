<?php // app/view/admin/user_edit.php ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit User: <?= e($user['name']) ?></h2>
  <a href="<?= BASE_URL ?>index.php?page=admin_users" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        
        <?php if (!empty($errors)): ?>
          <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger py-2 small">
              <?= e($err) ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
        
        <form method="POST">
          <div class="row g-3">
            
            <div class="col-12">
              <label class="form-label fw-semibold">Full Name *</label>
              <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email *</label>
              <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-semibold">Phone</label>
              <?php
              // Handle default empty string value safely without using complex short-hand operators
              $phoneValue = "";
              if (isset($user['phone'])) {
                  $phoneValue = $user['phone'];
              }
              ?>
              <input type="text" name="phone" class="form-control" value="<?= e($phoneValue) ?>">
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">Role</label>
              <select name="role" class="form-select">
                <?php 
                // Replaced advanced inline formatting loop with basic, explicit option check elements
                $allRoles = array('member', 'librarian', 'branch_manager', 'admin');
                
                foreach ($allRoles as $r): 
                    // Verify match with database record to set selected status
                    $roleSelected = "";
                    if ($user['role'] === $r) {
                        $roleSelected = "selected";
                    }
                    
                    // Simple manual conversion to transform programmatic snake-case into human-readable labels
                    if ($r === 'branch_manager') {
                        $roleLabel = "Branch Manager";
                    } else {
                        $roleLabel = ucfirst($r);
                    }
                ?>
                  <option value="<?= $r ?>" <?= $roleSelected ?>><?= e($roleLabel) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">Branch</label>
              <select name="branch_id" class="form-select">
                <option value="">-- None --</option>
                
                <?php foreach ($branches as $b): ?>
                  <?php
                  // Match condition verification logic
                  $branchSelected = "";
                  if ($user['branch_id'] == $b['id']) {
                      $branchSelected = "selected";
                  }
                  ?>
                  <option value="<?= $b['id'] ?>" <?= $branchSelected ?>>
                    <?= e($b['name']) ?>
                  </option>
                <?php endforeach; ?>
                
              </select>
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">Status</label>
              <select name="is_active" class="form-select">
                <?php
                // Evaluate current active profile status variables sequentially
                $activeSelected = "";
                $inactiveSelected = "";
                
                if ($user['is_active'] == true) {
                    $activeSelected = "selected";
                } else {
                    $inactiveSelected = "selected";
                }
                ?>
                <option value="1" <?= $activeSelected ?>>Active</option>
                <option value="0" <?= $inactiveSelected ?>>Inactive</option>
              </select>
            </div>
            
            <div class="col-12">
              <label class="form-label fw-semibold">New Password <small class="text-muted">(leave blank to keep current)</small></label>
              <input type="password" name="new_password" class="form-control" minlength="6">
            </div>
            
            <div class="col-12 d-flex gap-2">
              <button type="submit" class="btn btn-dark px-4">Update User</button>
              <a href="<?= BASE_URL ?>index.php?page=admin_users" class="btn btn-outline-secondary">Cancel</a>
            </div>
            
          </div>
        </form>
        
      </div>
    </div>
  </div>
</div>