<?php
/* app/controller/admin/reports.php */

// 1. Security Check: Protect the route so only logged-in admins can access it
requireLogin('admin');

// 2. Include the database models needed for statistical reports
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

// 3. Initialize the statistical model objects using the database connection ($conn)
$borrowModel = new BorrowModel($conn);
$fineModel   = new FineModel($conn);

// 4. Fetch calculated metrics directly from predefined model functions
$monthlyBorrows = $borrowModel->monthlyStats();
$monthlyFines   = $fineModel->monthlyCollected();
$statsPerBranch = $borrowModel->statsPerBranch();

// 5. Raw SQL Query A: Retrieve the top 10 most borrowed book genres
$genreQuery = "SELECT g.name, COUNT(br.id) AS total ";
$genreQuery .= "FROM borrow_records br ";
$genreQuery .= "JOIN books b ON br.book_id = b.id ";
$genreQuery .= "JOIN genres g ON b.genre_id = g.id ";
$genreQuery .= "GROUP BY g.id ";
$genreQuery .= "ORDER BY total DESC LIMIT 10";

$genreResult = $conn->query($genreQuery);
$genreStats  = $genreResult->fetch_all(MYSQLI_ASSOC);

// 6. Raw SQL Query B: Track member registration growth trends over the last 12 months
$growthQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS new_members ";
$growthQuery .= "FROM users ";
$growthQuery .= "WHERE role = 'member' ";
$growthQuery .= "GROUP BY month ";
$growthQuery .= "ORDER BY month DESC LIMIT 12";

$growthResult = $conn->query($growthQuery);
$memberGrowth = $growthResult->fetch_all(MYSQLI_ASSOC);

// 7. Check for Export Mode (e.g., if the admin clicked a "Download CSV/Print" action button)
$exportMode = false;
if (isset($_GET['export'])) {
    $exportMode = true;
}

// 8. Define the page metadata header title
$pageTitle = 'Platform Reports';

// 9. Assemble and layout the user interface templates conditionally based on Export Mode
if ($exportMode === false) {
    // Only load the standard navbar header layout if we are NOT exporting raw data
    require __DIR__ . '/../../view/shared/header.php';
}

// Render the main report analytics content page view
require __DIR__ . '/../../view/admin/reports.php';

if ($exportMode === false) {
    // Only load the standard footer design script if we are NOT exporting raw data
    require __DIR__ . '/../../view/shared/footer.php';
}