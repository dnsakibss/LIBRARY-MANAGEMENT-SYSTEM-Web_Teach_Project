<?php
/* app/controller/admin/transfers.php */

requireLogin('admin');
require_once __DIR__ . '/../../model/Models.php';

$transferModel = new TransferModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transferId = (int)$_POST['transfer_id'];
    $newStatus  = $_POST['status'];
    
    $transferModel->updateStatus($transferId, $newStatus);
    
    setFlash('success', 'Transfer updated.');
    redirect('index.php?page=admin_transfers');
}

$transfers = $transferModel->getAll();
$pageTitle = 'All Transfer Requests';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/transfers.php';
require __DIR__ . '/../../view/shared/footer.php';