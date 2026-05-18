<?php /* app/controller/branch_manager/branch_edit.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/Models.php';
require_once __DIR__ . '/../../model/UserModel.php';
$branchId    = (int)($_GET['id'] ?? 0);
$branchModel = new BranchModel($conn);
$userModel   = new UserModel($conn);
$branch      = $branchModel->getById($branchId);
if (!$branch) { setFlash('danger','Branch not found.'); redirect('index.php?page=manager_branches'); }
$managers = $userModel->getUsersByRole('branch_manager');
$errors   = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name'       => trim($_POST['name'] ?? ''),
        'address'    => trim($_POST['address'] ?? ''),
        'city'       => trim($_POST['city'] ?? ''),
        'phone'      => trim($_POST['phone'] ?? ''),
        'manager_id' => (int)($_POST['manager_id'] ?? 0) ?: null,
        'is_active'  => (int)($_POST['is_active'] ?? 1),
    ];
    if (!$data['name']) $errors[] = 'Branch name is required.';
    if (empty($errors)) {
        $branchModel->update($branchId, $data);
        setFlash('success', 'Branch updated.');
        redirect('index.php?page=manager_branches');
    }
}
$pageTitle = 'Edit Branch';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/branch_edit.php';
require __DIR__ . '/../../view/shared/footer.php';
