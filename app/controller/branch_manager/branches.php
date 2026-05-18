<?php /* app/controller/branch_manager/branches.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/Models.php';
$branchModel = new BranchModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branchModel->setActive((int)$_POST['branch_id'], (int)$_POST['is_active']);
    setFlash('success', 'Branch status updated.');
    redirect('index.php?page=manager_branches');
}
$branches  = $branchModel->getAll();
$pageTitle = 'Manage Branches';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/branches.php';
require __DIR__ . '/../../view/shared/footer.php';
