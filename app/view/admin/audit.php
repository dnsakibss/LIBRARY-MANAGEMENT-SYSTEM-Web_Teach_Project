<?php // app/view/admin/audit.php ?>

<h2 class="mb-4">
  <i class="bi bi-clock-history me-2"></i>Audit Log 
  <small class="text-muted fs-6">(Last 100 actions)</small>
</h2>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      
      <table class="table table-hover align-middle mb-0 small">
        <thead class="table-dark">
          <tr>
            <th>Type</th>
            <th>Actor</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
        
        <?php 
        // Loop through each audit entry record sequentially
        foreach ($auditLog as $entry): 
        ?>
        <tr>
          
          <td>
            <?php
            if ($entry['type'] === 'borrow') {
                $typeBadgeColor = "primary";
            } else {
                $typeBadgeColor = "warning text-dark";
            }
            ?>
            <span class="badge bg-<?= $typeBadgeColor ?>">
              <?= ucfirst($entry['type']) ?>
            </span>
          </td>
          
          <td><?= e($entry['actor']) ?></td>
          
          <td><?= e($entry['subject']) ?></td>
          
          <td>
            <?php 
            $statusValue = $entry['status'];
            $statusBadgeColor = "secondary"; // Fallback color value

            switch ($statusValue) {
                case 'active':
                case 'paid':
                    $statusBadgeColor = "success";
                    break;
                case 'returned':
                    $statusBadgeColor = "secondary";
                    break;
                case 'pending':
                    $statusBadgeColor = "warning";
                    break;
                case 'rejected':
                case 'unpaid':
                    $statusBadgeColor = "danger";
                    break;
                default:
                    $statusBadgeColor = "secondary";
                    break;
            }
            ?>
            <span class="badge bg-<?= $statusBadgeColor ?>">
              <?= ucfirst($entry['status']) ?>
            </span>
          </td>
          
          <td>
            <?php 
            $fullTimeValue = $entry['action_time'];
            $cleanTime = substr($fullTimeValue, 0, 16); 
            echo e($cleanTime);
            ?>
          </td>
          
        </tr>
        <?php endforeach; ?>
        
        <?php if (empty($auditLog)): ?>
        <tr>
          <td colspan="5" class="text-muted text-center py-4">No audit entries.</td>
        </tr>
        <?php endif; ?>
        
        </tbody>
      </table>
      
    </div>
  </div>
</div>