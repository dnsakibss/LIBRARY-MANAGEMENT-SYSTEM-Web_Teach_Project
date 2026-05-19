<?php
/* app/controller/admin/reports.php */

requireLogin('admin');
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

$borrowModel = new BorrowModel($conn);
$fineModel   = new FineModel($conn);

$monthlyBorrows = $borrowModel->monthlyStats();
$monthlyFines   = $fineModel->monthlyCollected();
$statsPerBranch = $borrowModel->statsPerBranch();

// Block: SQL execution to aggregate top 10 book categories
$genreQuery = "SELECT g.name, COUNT(br.id) AS total ";
$genreQuery .= "FROM borrow_records br ";
$genreQuery .= "JOIN books b ON br.book_id = b.id ";
$genreQuery .= "JOIN genres g ON b.genre_id = g.id ";
$genreQuery .= "GROUP BY g.id ";
$genreQuery .= "ORDER BY total DESC LIMIT 10";

$genreResult = $conn->query($genreQuery);
$genreStats  = $genreResult->fetch_all(MYSQLI_ASSOC);

// Block: SQL execution to track monthly member registration rates
$growthQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS new_members ";
$growthQuery .= "FROM users ";
$growthQuery .= "WHERE role = 'member' ";
$growthQuery .= "GROUP BY month ";
$growthQuery .= "ORDER BY month DESC LIMIT 12";

$growthResult = $conn->query($growthQuery);
$memberGrowth = $growthResult->fetch_all(MYSQLI_ASSOC);

// Block: Toggle layout inclusions if data export flag is verified
$exportMode = false;
if (isset($_GET['export'])) {
    $exportMode = true;
}

$pageTitle = 'Platform Reports';

if ($exportMode === false) {
    require __DIR__ . '/../../view/shared/header.php';
}

require __DIR__ . '/../../view/admin/reports.php';

if ($exportMode === false) {
    require __DIR__ . '/../../view/shared/footer.php';
}