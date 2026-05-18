<?php 
/* app/controller/librarian/stats.php */

// Only librarians should access branch statistics
requireLogin('librarian');

require_once __DIR__ . '/../../model/BookModel.php';

// Current branch context
$branchId = (int)$_SESSION['branch_id'];

$bookModel = new BookModel($conn);

// Most borrowed books in this branch
$mostBorrowed  = $bookModel->getMostBorrowedByBranch($branchId);

// Books that were never borrowed
$neverBorrowed = $bookModel->getNeverBorrowed($branchId);

// Borrow statistics grouped by genre
// Simple aggregation query for chart/table display
$r = $conn->query("
    SELECT g.name, COUNT(br.id) AS total 
    FROM borrow_records br
    JOIN books b ON br.book_id=b.id 
    JOIN genres g ON b.genre_id=g.id
    WHERE br.branch_id=$branchId 
    GROUP BY g.id 
    ORDER BY total DESC
");

// Convert result set into array
$genreStats = $r->fetch_all(MYSQLI_ASSOC);

// Page title for layout/view
$pageTitle = 'Catalog Statistics';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/stats.php';
require __DIR__ . '/../../view/shared/footer.php';