<?php /* app/controller/admin/user_edit.php */
requireLogin('admin');
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/Models.php';
$userId      = (int)($_GET['id'] ?? 0);
$userModel   = new UserModel($conn);
$branchModel = new BranchModel($conn);
$user        = $userModel->findById($userId);
if (!$user) { setFlash('danger','User not found.'); redirect('index.php?page=admin_users'); }
$branches = $branchModel->getAll();
$errors   = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name'      => trim($_POST['name'] ?? ''),
        'email'     => trim($_POST['email'] ?? ''),
        'phone'     => trim($_POST['phone'] ?? ''),
        'role'      => $_POST['role'] ?? $user['role'],
        'branch_id' => (int)($_POST['branch_id'] ?? 0) ?: null,
        'is_active' => (int)($_POST['is_active'] ?? 1),
    ];
    if (!$data['name'])  $errors[] = 'Name required.';
    if (!$data['email']) $errors[] = 'Email required.';
    if ($userModel->emailExists($data['email'], $userId)) $errors[] = 'Email already in use.';
    if (empty($errors)) {
        $userModel->updateFull($userId, $data);
        if (!empty($_POST['new_password']) && strlen($_POST['new_password']) >= 6) {
            $userModel->updatePassword($userId, $_POST['new_password']);
        }
        setFlash('success', 'User updated.');
        redirect('index.php?page=admin_users');
    }
}
$pageTitle = 'Edit User';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/user_edit.php';
require __DIR__ . '/../../view/shared/footer.php';
