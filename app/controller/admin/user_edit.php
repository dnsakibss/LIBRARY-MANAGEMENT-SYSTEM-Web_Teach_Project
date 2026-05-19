<?php
/* app/controller/admin/user_edit.php */

requireLogin('admin');
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/Models.php';

$userId      = (int)($_GET['id'] ?? 0);
$userModel   = new UserModel($conn);
$branchModel = new BranchModel($conn);
$user        = $userModel->findById($userId);

// Verify that the requested user exists in the system
if ($user === null) { 
    setFlash('danger', 'User not found.'); 
    redirect('index.php?page=admin_users'); 
}

$branches = $branchModel->getAll();
$errors   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Standardize branch parsing: maps 0 to a database NULL
    $branchIdInput = (int)($_POST['branch_id'] ?? 0);
    $finalBranchId = null;
    if ($branchIdInput > 0) {
        $finalBranchId = $branchIdInput;
    }

    $data = [
        'name'      => trim($_POST['name'] ?? ''),
        'email'     => trim($_POST['email'] ?? ''),
        'phone'     => trim($_POST['phone'] ?? ''),
        'role'      => $_POST['role'] ?? $user['role'],
        'branch_id' => $finalBranchId,
        'is_active' => (int)($_POST['is_active'] ?? 1),
    ];

    // Major Block: Information Integrity Validations
    if ($data['name'] === '') {
        $errors[] = 'Name required.';
    }
    if ($data['email'] === '') {
        $errors[] = 'Email required.';
    }
    if ($userModel->emailExists($data['email'], $userId)) {
        $errors[] = 'Email already in use.';
    }

    // Major Block: Execute Database Records Updates
    if (empty($errors)) {
        $userModel->updateFull($userId, $data);

        // Check if a new password string override has been typed in
        $newPassword = $_POST['new_password'] ?? '';
        if ($newPassword !== '' && strlen($newPassword) >= 6) {
            $userModel->updatePassword($userId, $newPassword);
        }

        setFlash('success', 'User updated.');
        redirect('index.php?page=admin_users');
    }
}

$pageTitle = 'Edit User';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/user_edit.php';
require __DIR__ . '/../../view/shared/footer.php';