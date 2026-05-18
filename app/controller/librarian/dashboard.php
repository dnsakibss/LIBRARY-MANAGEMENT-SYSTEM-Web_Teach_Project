<?php 
// app/controller/librarian/dashboard.php

// Only logged-in librarians can access this page
requireLogin('librarian');

// Load required models (books, borrow, fines etc.)
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

// Current branch context (important for multi-branch system)
$branchId    = (int)$_SESSION['branch_id'];

// Model instances (DB connection is already available in $conn)
$bookModel   = new BookModel($conn);
$borrowModel = new BorrowModel($conn);
$fineModel   = new FineModel($conn);

// Fetch borrow requests that are still pending approval
$pendingRequests = $borrowModel->getByBranch($branchId, 'pending');

// Books currently issued and active
$activeLoans     = $borrowModel->getByBranch($branchId, 'active');

// Overdue list (active + overdue filter applied)
$overdueLoans    = $borrowModel->getByBranch($branchId, 'active', 'overdue');

// Fines that are not yet paid (simple filter on returned data)
$unpaidFines     = array_filter(
    $fineModel->getByBranch($branchId),
    fn($f) => !$f['is_paid']
);

// Page title for dashboard view
$pageTitle = 'Librarian Dashboard';

// Load UI components (header → dashboard → footer)
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/dashboard.php';
require __DIR__ . '/../../view/shared/footer.php';