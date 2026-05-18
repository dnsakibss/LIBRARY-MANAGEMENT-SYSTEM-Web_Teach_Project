<?php /* app/controller/branch_manager/policies.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/Models.php';
$branchModel = new BranchModel($conn);
$branches    = $branchModel->getAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bid = (int)$_POST['branch_id'];
    $branchModel->savePolicy($bid, [
        'max_borrow_days'      => (int)$_POST['max_borrow_days'],
        'max_books_per_member' => (int)$_POST['max_books_per_member'],
        'fine_rate_per_day'    => (float)$_POST['fine_rate_per_day'],
        'max_renewals'         => (int)$_POST['max_renewals'],
    ]);
    setFlash('success', 'Policy saved.');
    redirect('index.php?page=manager_policies');
}
// Fetch policy for each branch
$policies = [];
foreach ($branches as $b) {
    $policies[$b['id']] = $branchModel->getPolicy((int)$b['id']);
}
$pageTitle = 'Branch Policies';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/policies.php';
require __DIR__ . '/../../view/shared/footer.php';
