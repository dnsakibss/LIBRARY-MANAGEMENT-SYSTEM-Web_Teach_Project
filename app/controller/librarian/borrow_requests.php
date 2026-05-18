<?php
// app/controller/librarian/borrow_requests.php

// Restrict page access to librarians only
requireLogin('librarian');

require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

// Logged-in librarian context
$branchId    = (int)$_SESSION['branch_id'];
$librarianId = (int)$_SESSION['user_id'];

$borrowModel = new BorrowModel($conn);
$bookModel   = new BookModel($conn);
$branchModel = new BranchModel($conn);

// Safety fallback:
// If librarian has no assigned branch yet, still allow viewing requests
// instead of blocking access completely
if ($branchId === 0) {

    $noBranchWarning = true;

    // Load all pending requests across branches
    $requests = $borrowModel->getAllPending();

} else {

    $noBranchWarning = false;

    // Branch-specific borrowing policy
    $policy   = $branchModel->getPolicy($branchId);

    // Only pending requests for current branch
    $requests = $borrowModel->getByBranch($branchId, 'pending');
}

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $recordId = (int)$_POST['record_id'];
    $action   = $_POST['action'] ?? '';

    // Fetch selected borrow request
    $record   = $borrowModel->getById($recordId);

    // Prevent duplicate processing
    if ($record && $record['status'] === 'pending') {

        $recBranchId = (int)$record['branch_id'];

        // Policy depends on the request's branch
        $pol = $branchModel->getPolicy($recBranchId);

        if ($action === 'approve') {

            // Ensure copies are available before issuing
            if ($bookModel->availableCopies(
                (int)$record['book_id'], 
                $recBranchId
            ) > 0) {

                // Approve request and set due date
                $borrowModel->approve(
                    $recordId, 
                    $librarianId, 
                    (int)$pol['max_borrow_days']
                );

                // Reduce available stock count
                $bookModel->decrementCopies(
                    (int)$record['book_id'], 
                    $recBranchId
                );

                setFlash(
                    'success', 
                    'Borrow request approved. Book issued for ' 
                    . $pol['max_borrow_days'] . ' days.'
                );

            } else {

                // No available inventory at that branch
                setFlash(
                    'danger', 
                    'No copies available at that branch. Cannot approve.'
                );
            }

        } elseif ($action === 'reject') {

            // Mark request as rejected
            $borrowModel->reject($recordId, $librarianId);

            setFlash('info', 'Borrow request rejected.');
        }
    }

    // Redirect to avoid accidental form resubmission
    redirect('index.php?page=librarian_requests');
}

// Page title for UI
$pageTitle = 'Borrow Requests';

// Load views
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/borrow_requests.php';
require __DIR__ . '/../../view/shared/footer.php';