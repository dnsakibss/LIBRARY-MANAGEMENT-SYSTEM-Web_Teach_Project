<?php
// app/controller/librarian/returns.php

// Only librarians can process returns
requireLogin('librarian');

require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

// Current branch context
$branchId    = (int)$_SESSION['branch_id'];

$borrowModel = new BorrowModel($conn);
$bookModel   = new BookModel($conn);
$fineModel   = new FineModel($conn);
$branchModel = new BranchModel($conn);

// Branch borrowing/fine policy
$policy      = $branchModel->getPolicy($branchId);

// Optional search input
$search = trim($_GET['search'] ?? '');

// Active loans for this branch
$allLoans = $borrowModel->getByBranch($branchId, 'active');

// Simple search filter (member name / book title / record id)
if ($search) {

    $loans = array_filter($allLoans, function($r) use ($search) {

        return stripos($r['member_name'], $search) !== false
            || stripos($r['book_title'], $search) !== false
            || (string)$r['id'] === $search;
    });

    // Reset array keys after filtering
    $loans = array_values($loans);

} else {

    $loans = $allLoans;
}

// Handle return action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $recordId = (int)$_POST['record_id'];

    // Fetch selected borrow record
    $record   = $borrowModel->getById($recordId);

    // Extra validation for safety
    if (
        $record &&
        $record['branch_id'] == $branchId &&
        $record['status'] === 'active'
    ) {

        // Mark book as returned
        $borrowModel->returnBook($recordId);

        // Increase available copies again
        $bookModel->incrementCopies(
            (int)$record['book_id'], 
            $branchId
        );

        // Calculate overdue days
        $daysOverdue = (int)floor(
            (strtotime('today') - strtotime($record['due_date'])) / 86400
        );

        // Auto-create fine if overdue
        if ($daysOverdue > 0) {

            $fineAmt = $daysOverdue * (float)$policy['fine_rate_per_day'];

            $fineModel->createOverdueFine(
                $recordId,
                (int)$record['member_id'],
                $branchId,
                $fineAmt
            );

            setFlash(
                'warning',
                "Book returned. Fine of ৳{$fineAmt} calculated for {$daysOverdue} overdue days."
            );

        } else {

            // Returned on time
            setFlash('success', 'Book returned successfully. No fine.');
        }
    }

    // Redirect prevents duplicate submissions on refresh
    redirect('index.php?page=librarian_returns');
}

// Page title for layout
$pageTitle = 'Process Returns';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/returns.php';
require __DIR__ . '/../../view/shared/footer.php';