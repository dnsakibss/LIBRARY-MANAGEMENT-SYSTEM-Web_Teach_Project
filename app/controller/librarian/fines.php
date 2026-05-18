<?php 
/* app/controller/librarian/fines.php */

// Restrict access to librarian users
requireLogin('librarian');

require_once __DIR__ . '/../../model/Models.php';
require_once __DIR__ . '/../../model/BorrowModel.php';

// Current branch of logged-in librarian
$branchId  = (int)$_SESSION['branch_id'];

$fineModel = new FineModel($conn);

// Handle fine-related actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // Mark existing fine as paid
    if ($action === 'mark_paid') {

        $fineModel->markPaid((int)$_POST['fine_id']);

        setFlash('success','Fine marked as paid.');

    } elseif ($action === 'manual_fine') {

        // Manual fine for damaged/lost books etc.
        $borrowModel = new BorrowModel($conn);

        $record = $borrowModel->getById((int)$_POST['record_id']);

        // Extra check to ensure record belongs to current branch
        if ($record && $record['branch_id'] == $branchId) {

            $fineModel->createManual(
                (int)$_POST['record_id'],
                (int)$record['member_id'],
                $branchId,
                (float)$_POST['amount'],
                trim($_POST['reason'] ?? 'Damaged/Lost')
            );

            setFlash('success','Manual fine issued.');
        }
    }

    // Redirect after action to avoid duplicate submissions
    redirect('index.php?page=librarian_fines');
}

// Fetch all fines for current branch
$fines = $fineModel->getByBranch($branchId);

// Page title for layout/view
$pageTitle = 'Manage Fines';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/fines.php';
require __DIR__ . '/../../view/shared/footer.php';