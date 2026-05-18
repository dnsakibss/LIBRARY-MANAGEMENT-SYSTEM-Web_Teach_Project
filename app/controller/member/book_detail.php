<?php
// app/controller/member/book_detail.php
requireLogin('member');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

$bookId   = (int)($_GET['id'] ?? 0);
$memberId = $_SESSION['user_id'];
$branchId = (int)$_SESSION['branch_id'];

$bookModel      = new BookModel($conn);
$reviewModel    = new ReviewModel($conn);
$readingModel   = new ReadingListModel($conn);
$reserveModel   = new ReservationModel($conn);
$branchModel    = new BranchModel($conn);

require_once __DIR__ . '/../../model/BorrowModel.php';
$borrowModel = new BorrowModel($conn);

$book = $bookModel->getById($bookId);
if (!$book) { setFlash('danger', 'Book not found.'); redirect('index.php?page=member_books'); }

$inventory = $bookModel->getWithInventory($bookId);
$reviews   = $reviewModel->getByBook($bookId);
$avgRating = $bookModel->getAverageRating($bookId);
$inList    = $readingModel->isInList($memberId, $bookId);

// Get borrow limit info for member's branch
$policy        = $branchModel->getPolicy($branchId);
$maxBooks      = (int)$policy['max_books_per_member'];
$currentActive = $borrowModel->countActiveForMember($memberId, $branchId);
$remaining     = max(0, $maxBooks - $currentActive);
$limitReached  = $currentActive >= $maxBooks;

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $rating = (int)$_POST['rating'];
    $text   = trim($_POST['review_text'] ?? '');
    if ($rating >= 1 && $rating <= 5) {
        $reviewModel->upsert($bookId, $memberId, $rating, $text);
        setFlash('success', 'Review saved.');
    }
    redirect("index.php?page=member_book_detail&id=$bookId");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {
    $reviewModel->delete($bookId, $memberId);
    setFlash('success', 'Review deleted.');
    redirect("index.php?page=member_book_detail&id=$bookId");
}

$pageTitle = e($book['title']);
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/book_detail.php';
require __DIR__ . '/../../view/shared/footer.php';
