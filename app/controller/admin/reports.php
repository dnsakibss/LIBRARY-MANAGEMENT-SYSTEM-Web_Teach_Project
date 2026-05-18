<?php /* app/controller/admin/reports.php */
requireLogin('admin');
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';
$borrowModel = new BorrowModel($conn);
$fineModel   = new FineModel($conn);
$monthlyBorrows  = $borrowModel->monthlyStats();
$monthlyFines    = $fineModel->monthlyCollected();
$statsPerBranch  = $borrowModel->statsPerBranch();
// Most borrowed genres
$r1 = $conn->query("SELECT g.name, COUNT(br.id) AS total FROM borrow_records br
    JOIN books b ON br.book_id=b.id JOIN genres g ON b.genre_id=g.id
    GROUP BY g.id ORDER BY total DESC LIMIT 10");
$genreStats = $r1->fetch_all(MYSQLI_ASSOC);
// Member growth
$r2 = $conn->query("SELECT DATE_FORMAT(created_at,'%Y-%m') AS month, COUNT(*) AS new_members
    FROM users WHERE role='member' GROUP BY month ORDER BY month DESC LIMIT 12");
$memberGrowth = $r2->fetch_all(MYSQLI_ASSOC);
$exportMode = isset($_GET['export']);
$pageTitle  = 'Platform Reports';
if (!$exportMode) require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/reports.php';
if (!$exportMode) require __DIR__ . '/../../view/shared/footer.php';
