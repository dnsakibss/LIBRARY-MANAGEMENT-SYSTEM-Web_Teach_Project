<?php /* app/controller/admin/users.php */
requireLogin('admin');
require_once __DIR__ . '/../../model/UserModel.php';
$userModel = new UserModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $uid    = (int)$_POST['user_id'];
    if ($action === 'activate')   $userModel->setActive($uid, 1);
    if ($action === 'deactivate') $userModel->setActive($uid, 0);
    if ($action === 'set_role')   $userModel->setRole($uid, $_POST['role']);
    setFlash('success', 'User updated.');
    redirect('index.php?page=admin_users');
}
$search = trim($_GET['search'] ?? '');
$users  = $userModel->getAllUsers($search);
$pageTitle = 'Manage Users';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/users.php';
require __DIR__ . '/../../view/shared/footer.php';
