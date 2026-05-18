<?php /* app/controller/librarian/transfers.php */
requireLogin('librarian');
require_once __DIR__ . '/../../model/Models.php';
require_once __DIR__ . '/../../model/BookModel.php';
$branchId     = (int)$_SESSION['branch_id'];
$transferModel = new TransferModel($conn);
$branchModel   = new BranchModel($conn);
$bookModel     = new BookModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $transferModel->create((int)$_POST['book_id'], $branchId, (int)$_POST['to_branch_id'], (int)$_SESSION['user_id']);
        setFlash('success', 'Transfer request submitted.');
    } elseif ($action === 'update') {
        $transferModel->updateStatus((int)$_POST['transfer_id'], $_POST['status']);
        setFlash('success', 'Transfer status updated.');
    }
    redirect('index.php?page=librarian_transfers');
}
$transfers = $transferModel->getByBranch($branchId);
$branches  = $branchModel->getAll();
$books     = $bookModel->getAll();
$pageTitle = 'Inter-Branch Transfers';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/transfers.php';
require __DIR__ . '/../../view/shared/footer.php';
