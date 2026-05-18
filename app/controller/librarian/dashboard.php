<?php
// app/controller/librarian/dashboard.php
requireLogin('librarian');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

$branchId    = (int)$_SESSION['branch_id'];
$bookModel   = new BookModel($conn);
$borrowModel = new BorrowModel($conn);
$fineModel   = new FineModel($conn);

$pendingRequests = $borrowModel->getByBranch($branchId, 'pending');
$activeLoans     = $borrowModel->getByBranch($branchId, 'active');
$overdueLoans    = $borrowModel->getByBranch($branchId, 'active', 'overdue');
$unpaidFines     = array_filter($fineModel->getByBranch($branchId), fn($f) => !$f['is_paid']);

$pageTitle = 'Librarian Dashboard';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/dashboard.php';
require __DIR__ . '/../../view/shared/footer.php';
