<?php /* app/controller/branch_manager/transfers.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/Models.php';
require_once __DIR__ . '/../../model/BookModel.php';
$transferModel = new TransferModel($conn);
$bookModel     = new BookModel($conn);
$branchModel   = new BranchModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transferModel->updateStatus((int)$_POST['transfer_id'], $_POST['status']);
    setFlash('success', 'Transfer request updated.');
    redirect('index.php?page=manager_transfers');
}
$transfers = $transferModel->getAll();
$pageTitle = 'Inter-Branch Transfers';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/transfers.php';
require __DIR__ . '/../../view/shared/footer.php';
