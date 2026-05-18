<?php /* app/controller/branch_manager/inventory_report.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

$bookModel   = new BookModel($conn);
$branchModel = new BranchModel($conn);
$branches    = $branchModel->getAll();

// Handle inventory update POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId   = (int)$_POST['book_id'];
    $branchId = (int)$_POST['branch_id'];
    $total    = (int)$_POST['total'];
    $available = (int)$_POST['available'];

    if ($available > $total) $available = $total;
    if ($total > 0) {
        $bookModel->upsertInventory($bookId, $branchId, $total, $available);
        setFlash('success', 'Inventory updated successfully.');
    }
    redirect('index.php?page=manager_inventory');
}

$selectedBranch = (int)($_GET['branch_id'] ?? ($branches[0]['id'] ?? 0));
$inventory      = $bookModel->getCrossBranchInventory();
$allBooks       = $bookModel->getAll();

// Group inventory by branch
$inventoryByBranch = [];
foreach ($inventory as $row) {
    $inventoryByBranch[$row['branch_name']][] = $row;
}

$pageTitle = 'Inventory Management';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/inventory_report.php';
require __DIR__ . '/../../view/shared/footer.php';
