<?php
/* app/controller/admin/dashboard.php */

// 1. Security Check: Only allow logged-in administrators to view this page
requireLogin('admin');

// 2. Include database model files so we can fetch data
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

// 3. Initialize the models using the global database connection variable ($conn)
$userModel   = new UserModel($conn);
$bookModel   = new BookModel($conn);
$borrowModel = new BorrowModel($conn);
$fineModel   = new FineModel($conn);

// 4. Fetch statistics from the database using individual model functions
$totalMembers     = $userModel->totalByRole('member');
$totalBooks       = $bookModel->totalCount();
$activeLoans      = $borrowModel->totalActive();
$overdueLoans     = $borrowModel->totalOverdue();
$outstandingFines = $fineModel->totalOutstanding();
$librarians       = $userModel->totalByRole('librarian');
$branchManagers   = $userModel->totalByRole('branch_manager');

// 5. Package all retrieved counts into an associative array for the view file to display
$stats = [
    'total_members'     => $totalMembers,
    'total_books'       => $totalBooks,
    'active_loans'      => $activeLoans,
    'overdue_loans'     => $overdueLoans,
    'outstanding_fines' => $outstandingFines,
    'librarians'        => $librarians,
    'branch_managers'   => $branchManagers,
];

// 6. Get the list of current active loans across all branches for the dashboard table
$recentActivity = $borrowModel->getAllActive();

// 7. Set the title of the page
$pageTitle = 'Admin Dashboard';

// 8. Load the layout components and the main view file to render the page
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/dashboard.php';
require __DIR__ . '/../../view/shared/footer.php';