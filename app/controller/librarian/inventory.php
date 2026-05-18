<?php 
/* app/controller/librarian/inventory.php */

// Only logged-in librarians should manage inventory
requireLogin('librarian');

require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

// Current librarian's branch
$branchId  = (int)$_SESSION['branch_id'];

$bookModel = new BookModel($conn);

// Handle inventory update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Insert or update inventory record for this branch
    $bookModel->upsertInventory(
        (int)$_POST['book_id'],
        $branchId,
        (int)$_POST['total'],
        (int)$_POST['available']
    );

    // Quick success message
    setFlash('success','Inventory updated.');

    // Prevent form resubmission on refresh
    redirect('index.php?page=librarian_inventory');
}

// Fetch all books for dropdown/list display
$books     = $bookModel->getAll();

// Inventory data specific to current branch
$inventory = $bookModel->getInventoryByBranch($branchId);

// Page title for layout
$pageTitle = 'Branch Inventory';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/inventory.php';
require __DIR__ . '/../../view/shared/footer.php';