<?php /* app/controller/branch_manager/branch_add.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/Models.php';
require_once __DIR__ . '/../../model/UserModel.php';
$branchModel = new BranchModel($conn);
$userModel   = new UserModel($conn);
$managers    = $userModel->getUsersByRole('branch_manager');
$errors = []; $old = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST;
    $name = trim($_POST['name'] ?? '');
    $addr = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if (!$name) $errors[] = 'Branch name is required.';
    if (!$city) $errors[] = 'City is required.';
    if (empty($errors)) {
        $newId = $branchModel->add(['name'=>$name,'address'=>$addr,'city'=>$city,'phone'=>$phone]);
        // Default policy
        $branchModel->savePolicy($newId, ['max_borrow_days'=>14,'max_books_per_member'=>5,'fine_rate_per_day'=>5.00,'max_renewals'=>2]);
        setFlash('success', 'Branch added.');
        redirect('index.php?page=manager_branches');
    }
}
$pageTitle = 'Add Branch';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/branch_add.php';
require __DIR__ . '/../../view/shared/footer.php';
