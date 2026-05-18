<?php /* app/controller/librarian/fines.php */
requireLogin('librarian');
require_once __DIR__ . '/../../model/Models.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
$branchId  = (int)$_SESSION['branch_id'];
$fineModel = new FineModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'mark_paid') {
        $fineModel->markPaid((int)$_POST['fine_id']);
        setFlash('success','Fine marked as paid.');
    } elseif ($action === 'manual_fine') {
        $borrowModel = new BorrowModel($conn);
        $record = $borrowModel->getById((int)$_POST['record_id']);
        if ($record && $record['branch_id'] == $branchId) {
            $fineModel->createManual((int)$_POST['record_id'], (int)$record['member_id'],
                $branchId, (float)$_POST['amount'], trim($_POST['reason'] ?? 'Damaged/Lost'));
            setFlash('success','Manual fine issued.');
        }
    }
    redirect('index.php?page=librarian_fines');
}
$fines = $fineModel->getByBranch($branchId);
$pageTitle = 'Manage Fines';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/fines.php';
require __DIR__ . '/../../view/shared/footer.php';
