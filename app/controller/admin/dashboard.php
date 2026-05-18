<?php /* app/controller/admin/dashboard.php */
requireLogin('admin');
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';
$userModel   = new UserModel($conn);
$bookModel   = new BookModel($conn);
$borrowModel = new BorrowModel($conn);
$fineModel   = new FineModel($conn);
$stats = [
    'total_members'  => $userModel->totalByRole('member'),
    'total_books'    => $bookModel->totalCount(),
    'active_loans'   => $borrowModel->totalActive(),
    'overdue_loans'  => $borrowModel->totalOverdue(),
    'outstanding_fines' => $fineModel->totalOutstanding(),
    'librarians'     => $userModel->totalByRole('librarian'),
    'branch_managers'=> $userModel->totalByRole('branch_manager'),
];
$recentActivity = $borrowModel->getAllActive();
$pageTitle = 'Admin Dashboard';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/dashboard.php';
require __DIR__ . '/../../view/shared/footer.php';
