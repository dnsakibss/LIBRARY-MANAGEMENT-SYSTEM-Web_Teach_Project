<?php /* app/controller/admin/branches.php */
requireLogin('admin');
require_once __DIR__ . '/../../model/Models.php';
$branchModel = new BranchModel($conn);
$branches    = $branchModel->getAll();
$pageTitle   = 'All Branches';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/branches.php';
require __DIR__ . '/../../view/shared/footer.php';
