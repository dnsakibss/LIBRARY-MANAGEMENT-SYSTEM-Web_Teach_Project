<?php /* app/controller/branch_manager/stats.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';
$borrowModel = new BorrowModel($conn);
$bookModel   = new BookModel($conn);
$fineModel   = new FineModel($conn);
$statsPerBranch  = $borrowModel->statsPerBranch();
$finesPerBranch  = $fineModel->outstandingPerBranch();
$mostBorrowedAll = $bookModel->getMostBorrowedByBranch(0, 10); // 0 = all branches
// Most borrowed across all branches
$r = $conn->query("SELECT b.title, b.author, COUNT(br.id) AS borrow_count
    FROM borrow_records br JOIN books b ON br.book_id=b.id
    WHERE br.status IN ('active','returned')
    GROUP BY b.id ORDER BY borrow_count DESC LIMIT 10");
$mostBorrowedAll = $r->fetch_all(MYSQLI_ASSOC);

// Members with outstanding fines
$r2 = $conn->query("SELECT u.name, u.email, SUM(f.amount) AS total_fines
    FROM fines f JOIN users u ON f.member_id=u.id WHERE f.is_paid=0
    GROUP BY u.id ORDER BY total_fines DESC LIMIT 10");
$membersWithFines = $r2->fetch_all(MYSQLI_ASSOC);

// Librarian activity
$r3 = $conn->query("SELECT u.name, br.name AS branch_name,
    COUNT(CASE WHEN rec.librarian_id=u.id THEN 1 END) AS processed
    FROM users u JOIN branches br ON u.branch_id=br.id
    LEFT JOIN borrow_records rec ON rec.librarian_id=u.id
    WHERE u.role='librarian' GROUP BY u.id ORDER BY processed DESC");
$librarianActivity = $r3->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Cross-Branch Statistics';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/stats.php';
require __DIR__ . '/../../view/shared/footer.php';
