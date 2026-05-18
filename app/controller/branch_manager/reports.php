<?php /* app/controller/branch_manager/reports.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';
$branchModel  = new BranchModel($conn);
$borrowModel  = new BorrowModel($conn);
$fineModel    = new FineModel($conn);
$branches     = $branchModel->getAll();
$selectedBranch = (int)($_GET['branch_id'] ?? (count($branches) ? $branches[0]['id'] : 0));

$monthlyBorrows = $borrowModel->monthlyReportByBranch($selectedBranch);
$finesCollected = $fineModel->monthlyCollected();

// New members per branch
$r = $conn->query("SELECT b.name AS branch_name, COUNT(u.id) AS new_members
    FROM branches b LEFT JOIN users u ON u.branch_id=b.id AND u.role='member'
    AND u.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY b.id ORDER BY b.name");
$newMembersPerBranch = $r->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Monthly Reports';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/reports.php';
require __DIR__ . '/../../view/shared/footer.php';
