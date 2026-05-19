<?php // app/view/admin/settings.php ?>

<h2 class="mb-4"><i class="bi bi-sliders me-2"></i>Platform Settings</h2>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        
        <form method="POST">
          <div class="row g-3">
            
            <div class="col-12">
              <label class="form-label fw-semibold">Allow Member Self-Registration</label>
              
              <select name="allow_self_register" class="form-select">
                <?php
                // Extract current self-registration status safely using separate fallback variables
                $regOption = "1";
                if (isset($settings['allow_self_register'])) {
                    $regOption = $settings['allow_self_register'];
                }

                // Explicit match checks to set selected dropdown option targets
                $yesSelected = "";
                $noSelected = "";
                if ($regOption === "1") {
                    $yesSelected = "selected";
                }
                if ($regOption === "0") {
                    $noSelected = "selected";
                }
                ?>
                <option value="1" <?= $yesSelected ?>>Yes — Members can register themselves</option>
                <option value="0" <?= $noSelected ?>>No — Admin must create member accounts</option>
              </select>
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">Default Fine Rate / Day (৳)</label>
              <?php
              $fineRate = "5.00";
              if (isset($settings['default_fine_rate'])) {
                  $fineRate = $settings['default_fine_rate'];
              }
              ?>
              <input type="number" name="default_fine_rate" class="form-control" step="0.50" value="<?= e($fineRate) ?>" min="0">
              <div class="form-text">Used if branch has no custom policy.</div>
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">Default Max Borrow Days</label>
              <?php
              $maxDays = "14";
              if (isset($settings['default_max_days'])) {
                  $maxDays = $settings['default_max_days'];
              }
              ?>
              <input type="number" name="default_max_days" class="form-control" value="<?= e($maxDays) ?>" min="1">
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold">Default Max Books / Member</label>
              <?php
              $maxBooks = "5";
              if (isset($settings['default_max_books'])) {
                  $maxBooks = $settings['default_max_books'];
              }
              ?>
              <input type="number" name="default_max_books" class="form-control" value="<?= e($maxBooks) ?>" min="1">
            </div>
            
            <div class="col-12">
              <button type="submit" class="btn btn-dark px-4">
                <i class="bi bi-save me-1"></i>Save Settings
              </button>
            </div>
            
          </div>
        </form>
        
      </div>
    </div>
  </div>
</div>