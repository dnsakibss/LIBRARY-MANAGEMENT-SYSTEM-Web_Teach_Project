<?php /* app/controller/librarian/active_loans.php */
requireLogin('librarian');
require_once __DIR__ . '/../../model/BorrowModel.php';
$branchId  = (int)$_SESSION['branch_id'];
$filter    = $_GET['filter'] ?? '';
$borrowModel = new BorrowModel($conn);
$loans     = $borrowModel->getByBranch($branchId, 'active', $filter);
$pageTitle = 'Active Loans';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/active_loans.php';
require __DIR__ . '/../../view/shared/footer.php';
