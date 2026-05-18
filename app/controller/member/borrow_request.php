<?php
// app/controller/member/borrow_request.php
requireLogin('member');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

$bookId   = (int)($_POST['book_id']   ?? $_GET['book_id']   ?? 0);
// Use posted branch_id, but fall back to member's own branch if missing/zero
$branchId = (int)($_POST['branch_id'] ?? 0);
if ($branchId === 0) {
    $branchId = (int)$_SESSION['branch_id'];
}
$memberId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookModel   = new BookModel($conn);
    $borrowModel = new BorrowModel($conn);
    $branchModel = new BranchModel($conn);

    $avail   = $bookModel->availableCopies($bookId, $branchId);
    $policy  = $branchModel->getPolicy($branchId);
    $active  = $borrowModel->countActiveForMember($memberId, $branchId);
    $already = $borrowModel->hasActiveBorrow($memberId, $bookId, $branchId);

    if (!$bookId) {
        setFlash('danger', 'Invalid book.');
    } elseif (!$branchId) {
        setFlash('danger', 'Please select a branch.');
    } elseif ($already) {
        setFlash('warning', 'You already have an active borrow/request for this book at this branch.');
    } elseif ($active >= $policy['max_books_per_member']) {
        setFlash('danger', "You have reached the maximum borrow limit ({$policy['max_books_per_member']}) for this branch.");
    } elseif ($avail < 1) {
        $reserveModel = new ReservationModel($conn);
        if ($reserveModel->hasReservation($memberId, $bookId, $branchId)) {
            setFlash('warning', 'You are already on the waitlist for this book.');
        } else {
            $reserveModel->reserve($memberId, $bookId, $branchId);
            setFlash('info', 'No copies available. You have been added to the reservation waitlist.');
        }
    } else {
        $borrowModel->createRequest($memberId, $bookId, $branchId);
        setFlash('success', 'Borrow request submitted. Awaiting librarian approval.');
    }
    redirect("index.php?page=member_book_detail&id=$bookId");
}
redirect('index.php?page=member_books');
