<?php /* app/controller/branch_manager/dashboard.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';
$borrowModel  = new BorrowModel($conn);
$fineModel    = new FineModel($conn);
$branchModel  = new BranchModel($conn);
$statsPerBranch    = $borrowModel->statsPerBranch();
$finesPerBranch    = $fineModel->outstandingPerBranch();
$overdueAll        = $borrowModel->getAllOverdue(); //new added code 
$branches          = $branchModel->getAll();
$pageTitle = 'Branch Manager Dashboard';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/dashboard.php';
require __DIR__ . '/../../view/shared/footer.php';
