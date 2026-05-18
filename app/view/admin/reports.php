<?php // app/view/admin/reports.php
$exportMode = isset($_GET['export']);
if ($exportMode): ?>
<!DOCTYPE html><html><head><title>Report Export</title>
<style>body{font-family:Arial,sans-serif;font-size:12px}table{border-collapse:collapse;width:100%}th,td{border:1px solid #ccc;padding:6px}th{background:#333;color:#fff}h2{margin-top:20px}</style>
</head><body>
<h1>Library Management System — Platform Report</h1>
<p>Generated: <?= date('d M Y H:i') ?></p>
<?php else: ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-graph-up me-2"></i>Platform-Wide Reports</h2>
  <a href="?page=admin_reports&export=1" class="btn btn-dark" target="_blank"><i class="bi bi-printer me-1"></i>Export / Print</a>
</div>
<div class="row g-4">
<?php endif; ?>

<?php
$section = function($title, $headers, $rows, $emptyMsg = 'No data.') use ($exportMode) {
  echo $exportMode ? "<h2>$title</h2>" : '<div class="col-md-6"><div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">' . $title . '</div><div class="card-body p-0">';
  echo '<div class="table-responsive"><table class="table table-hover mb-0 small"><thead class="table-light"><tr>';
  foreach ($headers as $h) echo "<th>$h</th>";
  echo '</tr></thead><tbody>';
  if (empty($rows)) {
    echo '<tr><td colspan="' . count($headers) . '" class="text-muted text-center py-3">' . $emptyMsg . '</td></tr>';
  } else {
    foreach ($rows as $row) {
      echo '<tr>';
      foreach ($row as $cell) echo '<td>' . htmlspecialchars((string)$cell, ENT_QUOTES) . '</td>';
      echo '</tr>';
    }
  }
  echo '</tbody></table></div>';
  if (!$exportMode) echo '</div></div></div>';
};
?>

<?php $section('Monthly Borrows', ['Month','Total'],
  array_map(fn($r) => [$r['month'], $r['total']], $monthlyBorrows)); ?>

<?php $section('Monthly Fines Collected', ['Month','৳ Collected'],
  array_map(fn($r) => [$r['month'], number_format($r['total'],2)], $monthlyFines)); ?>

<?php $section('Borrows per Branch', ['Branch','Active','Overdue'],
  array_map(fn($r) => [$r['branch_name'], $r['active_loans'], $r['overdue_loans']], $statsPerBranch)); ?>

<?php $section('Most Borrowed Genres', ['Genre','Total Borrows'],
  array_map(fn($r) => [$r['name'], $r['total']], $genreStats)); ?>

<?php $section('Member Growth (Monthly)', ['Month','New Members'],
  array_map(fn($r) => [$r['month'], $r['new_members']], $memberGrowth)); ?>

<?php if ($exportMode): ?>
<script>window.onload=function(){window.print();}</script>
</body></html>
<?php else: ?>
</div>
<?php endif; ?>
