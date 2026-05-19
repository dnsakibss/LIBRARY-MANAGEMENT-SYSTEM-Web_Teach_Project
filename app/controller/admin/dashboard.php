<?php
/* app/controller/admin/dashboard.php */

requireLogin('admin');
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

$userModel   = new UserModel($conn);
$bookModel   = new BookModel($conn);
$borrowModel = new BorrowModel($conn);
$fineModel   = new FineModel($conn);

// Block: Retrieve independent data statistics from individual models
$totalMembers     = $userModel->totalByRole('member');
$totalBooks       = $bookModel->totalCount();
$activeLoans      = $borrowModel->totalActive();
$overdueLoans     = $borrowModel->totalOverdue();
$outstandingFines = $fineModel->totalOutstanding();
$librarians       = $userModel->totalByRole('librarian');
$branchManagers   = $userModel->totalByRole('branch_manager');

$stats = [
    'total_members'     => $totalMembers,
    'total_books'       => $totalBooks,
    'active_loans'      => $activeLoans,
    'overdue_loans'     => $overdueLoans,
    'outstanding_fines' => $outstandingFines,
    'librarians'        => $librarians,
    'branch_managers'   => $branchManagers,
];

$recentActivity = $borrowModel->getAllActive();
$pageTitle      = 'Admin Dashboard';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/dashboard.php';
require __DIR__ . '/../../view/shared/footer.php';