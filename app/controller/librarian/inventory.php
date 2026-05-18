<?php /* app/controller/librarian/inventory.php */
requireLogin('librarian');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';
$branchId  = (int)$_SESSION['branch_id'];
$bookModel = new BookModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookModel->upsertInventory((int)$_POST['book_id'], $branchId, (int)$_POST['total'], (int)$_POST['available']);
    setFlash('success','Inventory updated.'); redirect('index.php?page=librarian_inventory');
}
$books     = $bookModel->getAll();
$inventory = $bookModel->getInventoryByBranch($branchId);
$pageTitle = 'Branch Inventory';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/inventory.php';
require __DIR__ . '/../../view/shared/footer.php';
