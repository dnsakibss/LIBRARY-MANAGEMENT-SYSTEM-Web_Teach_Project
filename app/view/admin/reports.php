<?php // app/view/admin/reports.php

// Check if the user wants to export/print the report
// We check if 'export' is written in the URL query string
$exportMode = false;
if (isset($_GET['export'])) {
    $exportMode = true;
}

if ($exportMode == true): 
?>
  <!DOCTYPE html>
  <html>
  <head>
    <title>Report Export</title>
    <style>
      body { font-family: Arial, sans-serif; font-size: 12px; }
      table { border-collapse: collapse; width: 100%; }
      th, td { border: 1px solid #ccc; padding: 6px; }
      th { background: #333; color: #fff; }
      h2 { margin-top: 20px; }
    </style>
  </head>
  <body>
    <h1>Library Management System — Platform Report</h1>
    <p>Generated: <?= date('d M Y H:i') ?></p>

<?php 
else: 
?>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-graph-up me-2"></i>Platform-Wide Reports</h2>
    <a href="?page=admin_reports&export=1" class="btn btn-dark" target="_blank">
      <i class="bi bi-printer me-1"></i>Export / Print
    </a>
  </div>
  
  <div class="row g-4">
<?php 
endif; 


// =================================================== 
// DATA TABLE RENDERING FUNCTIONS                      
// =================================================== 
// Replaced the complex anonymous array mapping function with a traditional student-friendly procedural approach.
// This function takes a section title, list of column headers, and data rows to build tables dynamically.
function renderReportSection($title, $headers, $rows, $exportMode) {
    
    // Step 1: Render the wrapper container tags based on the display mode
    if ($exportMode == true) {
        echo "<h2>" . $title . "</h2>";
    } else {
        echo '<div class="col-md-6">';
        echo '  <div class="card border-0 shadow-sm">';
        echo '    <div class="card-header bg-white fw-semibold">' . $title . '</div>';
        echo '    <div class="card-body p-0">';
    }
    
    // Step 2: Set up the HTML table template wrapper formatting
    echo '<div class="table-responsive">';
    echo '  <table class="table table-hover mb-0 small">';
    echo '    <thead class="table-light">';
    echo '      <tr>';
    
    // Print out table headers sequentially using a basic loop
    foreach ($headers as $h) {
        echo "<th>" . $h . "</th>";
    }
    
    echo '      </tr>';
    echo '    </thead>';
    echo '    <tbody>';
    
    // Step 3: Check if data arrays contain row parameters records
    if (empty($rows)) {
        $totalColumns = count($headers);
        echo '<tr><td colspan="' . $totalColumns . '" class="text-muted text-center py-3">No data.</td></tr>';
    } else {
        // Run through each data row record sequentially
        foreach ($rows as $row) {
            echo '<tr>';
            
            // Loop through individual data cells inside the active row
            foreach ($row as $cell) {
                // Cast to string parameters explicitly and sanitize text output safely
                $cleanCellString = htmlspecialchars((string)$cell, ENT_QUOTES);
                echo '<td>' . $cleanCellString . '</td>';
            }
            
            echo '</tr>';
        }
    }
    
    echo '    </tbody>';
    echo '  </table>';
    echo '</div>';
    
    // Step 4: Close the template wrapper elements safely if displaying on screen
    if ($exportMode == false) {
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
}


// =================================================== 
// FORMATTING AND PROCESSING DATA SECTION              
// =================================================== 

// 1. Monthly Borrows Table
$formattedMonthlyBorrows = array();
foreach ($monthlyBorrows as $r) {
    $formattedMonthlyBorrows[] = array($r['month'], $r['total']);
}
renderReportSection('Monthly Borrows', array('Month', 'Total'), $formattedMonthlyBorrows, $exportMode);


// 2. Monthly Fines Collected Table (Formatted to display currency parameters cleanly)
$formattedMonthlyFines = array();
foreach ($monthlyFines as $r) {
    $moneyString = number_format($r['total'], 2);
    $formattedMonthlyFines[] = array($r['month'], $moneyString);
}
renderReportSection('Monthly Fines Collected', array('Month', '৳ Collected'), $formattedMonthlyFines, $exportMode);


// 3. Borrows per Branch Table
$formattedBranchStats = array();
foreach ($statsPerBranch as $r) {
    $formattedBranchStats[] = array($r['branch_name'], $r['active_loans'], $r['overdue_loans']);
}
renderReportSection('Borrows per Branch', array('Branch', 'Active', 'Overdue'), $formattedBranchStats, $exportMode);


// 4. Most Borrowed Genres Table
$formattedGenreStats = array();
foreach ($genreStats as $r) {
    $formattedGenreStats[] = array($r['name'], $r['total']);
}
renderReportSection('Most Borrowed Genres', array('Genre', 'Total Borrows'), $formattedGenreStats, $exportMode);


// 5. Member Growth (Monthly) Table
$formattedGrowthStats = array();
foreach ($memberGrowth as $r) {
    $formattedGrowthStats[] = array($r['month'], $r['new_members']);
}
renderReportSection('Member Growth (Monthly)', array('Month', 'New Members'), $formattedGrowthStats, $exportMode);


// =================================================== 
// EXPORT MODE PREVIEW CONFIGURATION / PAGE FOOTER    
// =================================================== 
if ($exportMode == true): 
?>
  <script>
    window.onload = function() {
        window.print();
    }
  </script>
  </body>
  </html>
<?php 
else: 
?>
  </div> <?php 
endif; 
?>