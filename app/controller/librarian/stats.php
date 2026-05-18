<?php /* app/controller/librarian/stats.php */
requireLogin('librarian');
require_once __DIR__ . '/../../model/BookModel.php';
$branchId = (int)$_SESSION['branch_id'];
$bookModel = new BookModel($conn);
$mostBorrowed  = $bookModel->getMostBorrowedByBranch($branchId);
$neverBorrowed = $bookModel->getNeverBorrowed($branchId);
// Borrows per genre
$r = $conn->query("SELECT g.name, COUNT(br.id) AS total FROM borrow_records br
    JOIN books b ON br.book_id=b.id JOIN genres g ON b.genre_id=g.id
    WHERE br.branch_id=$branchId GROUP BY g.id ORDER BY total DESC");
$genreStats = $r->fetch_all(MYSQLI_ASSOC);
$pageTitle = 'Catalog Statistics';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/stats.php';
require __DIR__ . '/../../view/shared/footer.php';

