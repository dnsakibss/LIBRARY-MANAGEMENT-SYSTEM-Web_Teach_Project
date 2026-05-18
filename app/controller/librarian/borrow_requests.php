<?php
// app/controller/librarian/borrow_requests.php
requireLogin('librarian');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

$branchId    = (int)$_SESSION['branch_id'];
$librarianId = (int)$_SESSION['user_id'];
$borrowModel = new BorrowModel($conn);
$bookModel   = new BookModel($conn);
$branchModel = new BranchModel($conn);

// Safety check: if librarian has no branch assigned, show all pending
// so they are not locked out
if ($branchId === 0) {
    $noBranchWarning = true;
    // Get all pending requests across all branches
    $requests = $borrowModel->getAllPending();
} else {
    $noBranchWarning = false;
    $policy   = $branchModel->getPolicy($branchId);
    $requests = $borrowModel->getByBranch($branchId, 'pending');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recordId = (int)$_POST['record_id'];
    $action   = $_POST['action'] ?? '';
    $record   = $borrowModel->getById($recordId);

    if ($record && $record['status'] === 'pending') {
        $recBranchId = (int)$record['branch_id'];
        $pol = $branchModel->getPolicy($recBranchId);

        if ($action === 'approve') {
            if ($bookModel->availableCopies((int)$record['book_id'], $recBranchId) > 0) {
                $borrowModel->approve($recordId, $librarianId, (int)$pol['max_borrow_days']);
                $bookModel->decrementCopies((int)$record['book_id'], $recBranchId);
                setFlash('success', 'Borrow request approved. Book issued for ' . $pol['max_borrow_days'] . ' days.');
            } else {
                setFlash('danger', 'No copies available at that branch. Cannot approve.');
            }
        } elseif ($action === 'reject') {
            $borrowModel->reject($recordId, $librarianId);
            setFlash('info', 'Borrow request rejected.');
        }
    }
    redirect('index.php?page=librarian_requests');
}

$pageTitle = 'Borrow Requests';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/borrow_requests.php';
require __DIR__ . '/../../view/shared/footer.php';
