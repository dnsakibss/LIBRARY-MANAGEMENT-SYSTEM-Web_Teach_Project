<?php 
/* app/controller/librarian/active_loans.php */

// Only librarians can access active loan records
requireLogin('librarian');

require_once __DIR__ . '/../../model/BorrowModel.php';

// Current librarian branch
$branchId  = (int)$_SESSION['branch_id'];

// Optional filter (e.g. overdue)
$filter    = $_GET['filter'] ?? '';

$borrowModel = new BorrowModel($conn);

// Fetch active loan records for this branch
$loans     = $borrowModel->getByBranch(
    $branchId,
    'active',
    $filter
);

// Page title used by shared layout
$pageTitle = 'Active Loans';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/active_loans.php';
require __DIR__ . '/../../view/shared/footer.php';