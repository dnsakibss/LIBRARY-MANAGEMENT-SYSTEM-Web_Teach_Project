<?php // app/view/admin/transfers.php ?>

<h2 class="mb-4"><i class="bi bi-arrow-left-right me-2"></i>All Inter-Branch Transfer Requests</h2>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-dark">
          <tr>
            <th>Book</th>
            <th>From</th>
            <th>To</th>
            <th>Requested By</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        
        <?php 
        // CORE LOOP: Process through each transfer request sequentially
        foreach ($transfers as $t):
            
            // STATUS BADGE CONFIGURATION: Replaced the short-hand array lookup map with simple student-style if-else blocks
            $statusValue = $t['status'];
            $badgeColor = "secondary";
            $textColor = "white";

            if ($statusValue === 'pending') {
                $badgeColor = "warning";
                $textColor = "dark";
            } else if ($statusValue === 'approved') {
                $badgeColor = "success";
                $textColor = "white";
            } else if ($statusValue === 'rejected') {
                $badgeColor = "danger";
                $textColor = "white";
            } else if ($statusValue === 'completed') {
                $badgeColor = "secondary";
                $textColor = "white";
            }
        ?>
        <tr>
          <td><?= e($t['book_title']) ?></td>
          <td><?= e($t['from_branch']) ?></td>
          <td><?= e($t['to_branch']) ?></td>
          
          <td><?= e($t['requested_by_name']) ?></td>
          
          <td>
            <?php 
            $createdAtTime = $t['created_at'];
            $cleanDate = substr($createdAtTime, 0, 10); 
            echo e($cleanDate);
            ?>
          </td>
          
          <td>
            <span class="badge bg-<?= $badgeColor ?> text-<?= $textColor ?>">
              <?= ucfirst($t['status']) ?>
            </span>
          </td>
          
          <td>
            <?php 
            // Only allow updates if the status variable check equals 'pending'
            if ($t['status'] === 'pending'): 
            ?>
              <form method="POST" class="d-inline-flex gap-1">
                <input type="hidden" name="transfer_id" value="<?= $t['id'] ?>">
                
                <button name="status" value="approved" class="btn btn-xs btn-sm btn-success">Approve</button>
                <button name="status" value="rejected" class="btn btn-xs btn-sm btn-danger">Reject</button>
              </form>
            <?php 
            else: 
            ?>
              <span class="text-muted">—</span>
            <?php 
            endif; 
            ?>
          </td>
          
        </tr>
        <?php 
        endforeach; 
        ?>
        
        <?php if (empty($transfers)): ?>
          <tr>
            <td colspan="7" class="text-muted text-center py-4">No requests.</td>
          </tr>
        <?php endif; ?>
        
        </tbody>
      </table>
      
    </div>
  </div>
</div>