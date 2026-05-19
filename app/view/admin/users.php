<?php // app/view/admin/users.php ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-people me-2"></i>Manage Users</h2>
  <a href="<?= BASE_URL ?>index.php?page=admin_user_add" class="btn btn-dark">
    <i class="bi bi-person-plus me-1"></i>Create Staff
  </a>
</div>

<form method="GET" action="<?= BASE_URL ?>index.php" class="mb-3 d-flex gap-2">
  <input type="hidden" name="page" value="admin_users">
  <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone…" value="<?= e($search) ?>">
  <button class="btn btn-dark">Search</button>
  
  <?php 
  // Explicit checking configuration to render the reset link button if a search was processed
  if ($search != ""): 
  ?>
    <a href="<?= BASE_URL ?>index.php?page=admin_users" class="btn btn-outline-secondary">Clear</a>
  <?php endif; ?>
</form>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Branch</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        
        <?php 
        // CORE LOOP: Step sequentially through each available profile dictionary row record
        foreach ($users as $i => $u): 
        ?>
        <tr>
          <td class="text-muted"><?= $i + 1 ?></td>
          <td class="fw-semibold"><?= e($u['name']) ?></td>
          <td><?= e($u['email']) ?></td>
          
          <td>
            <?php 
            $userRole = $u['role'];
            $roleBadgeColor = "secondary"; // Fallback color property
            $roleCleanLabel = "";

            if ($userRole === 'admin') {
                $roleBadgeColor = "dark";
                $roleCleanLabel = "Admin";
            } else if ($userRole === 'branch_manager') {
                $roleBadgeColor = "danger";
                $roleCleanLabel = "Branch Manager";
            } else if ($userRole === 'librarian') {
                $roleBadgeColor = "success";
                $roleCleanLabel = "Librarian";
            } else if ($userRole === 'member') {
                $roleBadgeColor = "primary";
                $roleCleanLabel = "Member";
            } else {
                $roleCleanLabel = ucfirst($userRole);
            }
            ?>
            <span class="badge bg-<?= $roleBadgeColor ?>">
              <?= e($roleCleanLabel) ?>
            </span>
          </td>
          
          <td>
            <?php 
            $assignedBranchName = "—";
            if (isset($u['branch_name'])) {
                $assignedBranchName = $u['branch_name'];
            }
            echo e($assignedBranchName);
            ?>
          </td>
          
          <td>
            <?php 
            if ($u['is_active'] == true) {
                echo '<span class="badge bg-success">Active</span>';
            } else {
                echo '<span class="badge bg-secondary">Inactive</span>';
            }
            ?>
          </td>
          
          <td>
            <div class="d-flex gap-1">
              
              <a href="<?= BASE_URL ?>index.php?page=admin_user_edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-pencil"></i>
              </a>
              
              <form method="POST" class="d-inline">
                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                
                <?php 
                // Dynamically swap the target status variable actions and layout properties cleanly
                if ($u['is_active'] == true) {
                    $formActionTarget = "deactivate";
                    $buttonStyleClass = "secondary";
                    $iconStyleClass   = "person-dash";
                    $buttonTitleHint  = "Deactivate";
                } else {
                    $formActionTarget = "activate";
                    $buttonStyleClass = "success";
                    $iconStyleClass   = "person-check";
                    $buttonTitleHint  = "Activate";
                }
                ?>
                
                <input type="hidden" name="action" value="<?= $formActionTarget ?>">
                <button class="btn btn-sm btn-outline-<?= $buttonStyleClass ?>" title="<?= $buttonTitleHint ?>">
                  <i class="bi bi-<?= $iconStyleClass ?>"></i>
                </button>
              </form>
              
            </div>
          </td>
        </tr>
        <?php 
        endforeach; 
        ?>
        
        </tbody>
      </table>
    </div>
    
    <div class="card-footer bg-white text-muted small">
      Showing <?= count($users) ?> user(s)
    </div>
  </div>
</div>