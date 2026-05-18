<?php 
/* app/controller/librarian/transfers.php */

// Only librarians are allowed to manage transfers
requireLogin('librarian');

require_once __DIR__ . '/../../model/Models.php';
require_once __DIR__ . '/../../model/BookModel.php';

// Current branch context (source branch)
$branchId     = (int)$_SESSION['branch_id'];

$transferModel = new TransferModel($conn);
$branchModel   = new BranchModel($conn);
$bookModel     = new BookModel($conn);

// Handle transfer-related actions (create / update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    if ($action === 'create') {

        // Create new inter-branch transfer request
        $transferModel->create(
            (int)$_POST['book_id'],
            $branchId,
            (int)$_POST['to_branch_id'],
            (int)$_SESSION['user_id']
        );

        setFlash('success', 'Transfer request submitted.');

    } elseif ($action === 'update') {

        // Update transfer status (approved/rejected/completed etc.)
        $transferModel->updateStatus(
            (int)$_POST['transfer_id'],
            $_POST['status']
        );

        setFlash('success', 'Transfer status updated.');
    }

    // Redirect to avoid duplicate form submission
    redirect('index.php?page=librarian_transfers');
}

// Load transfer list for current branch
$transfers = $transferModel->getByBranch($branchId);

// Supporting dropdown data
$branches  = $branchModel->getAll();
$books     = $bookModel->getAll();

// Page title for view rendering
$pageTitle = 'Inter-Branch Transfers';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/transfers.php';
require __DIR__ . '/../../view/shared/footer.php';