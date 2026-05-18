<?php
// app/controller/librarian/returns.php
requireLogin('librarian');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

$branchId    = (int)$_SESSION['branch_id'];
$borrowModel = new BorrowModel($conn);
$bookModel   = new BookModel($conn);
$fineModel   = new FineModel($conn);
$branchModel = new BranchModel($conn);
$policy      = $branchModel->getPolicy($branchId);

$search = trim($_GET['search'] ?? '');

$allLoans = $borrowModel->getByBranch($branchId, 'active');
if ($search) {
    $loans = array_filter($allLoans, function($r) use ($search) {
        return stripos($r['member_name'], $search) !== false
            || stripos($r['book_title'], $search) !== false
            || (string)$r['id'] === $search;
    });
    $loans = array_values($loans); // re-index
} else {
    $loans = $allLoans;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recordId = (int)$_POST['record_id'];
    $record   = $borrowModel->getById($recordId);

    if ($record && $record['branch_id'] == $branchId && $record['status'] === 'active') {
        $borrowModel->returnBook($recordId);
        $bookModel->incrementCopies((int)$record['book_id'], $branchId);

        // Auto-calculate fine if overdue
        $daysOverdue = (int)floor((strtotime('today') - strtotime($record['due_date'])) / 86400);
        if ($daysOverdue > 0) {
            $fineAmt = $daysOverdue * (float)$policy['fine_rate_per_day'];
            $fineModel->createOverdueFine($recordId, (int)$record['member_id'], $branchId, $fineAmt);
            setFlash('warning', "Book returned. Fine of ৳{$fineAmt} calculated for {$daysOverdue} overdue days.");
        } else {
            setFlash('success', 'Book returned successfully. No fine.');
        }
    }
    redirect('index.php?page=librarian_returns');
}

$pageTitle = 'Process Returns';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/returns.php';
require __DIR__ . '/../../view/shared/footer.php';
