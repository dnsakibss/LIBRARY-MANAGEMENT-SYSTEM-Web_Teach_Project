<?php
// app/controller/librarian/profile.php  (also used for manager + admin via symlink pattern)
// Determine role from session
$role = $_SESSION['role'];
requireLogin($role);
require_once __DIR__ . '/../../model/UserModel.php';

$userId    = $_SESSION['user_id'];
$userModel = new UserModel($conn);
$user      = $userModel->findById($userId);
$errors    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'update_profile') {
        $data = ['name' => trim($_POST['name'] ?? ''), 'phone' => trim($_POST['phone'] ?? ''), 'branch_id' => $user['branch_id']];
        if (!$data['name']) $errors[] = 'Name is required.';
        if (empty($errors)) {
            if (!empty($_FILES['profile_pic']['name'])) {
                $ext   = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                $fname = 'profile_' . $userId . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], UPLOAD_DIR . 'profiles/' . $fname)) {
                    $userModel->updateProfilePic($userId, 'uploads/profiles/' . $fname);
                }
            }
            $userModel->updateProfile($userId, $data);
            $_SESSION['user_name'] = $data['name'];
            setFlash('success', 'Profile updated.');
            redirect("index.php?page={$role}_profile");
        }
    } elseif ($action === 'change_password') {
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        if (!password_verify($current, $user['password_hash'])) $errors[] = 'Current password incorrect.';
        if (strlen($new) < 6) $errors[] = 'New password must be at least 6 characters.';
        if ($new !== $confirm) $errors[] = 'Passwords do not match.';
        if (empty($errors)) {
            $userModel->updatePassword($userId, $new);
            setFlash('success', 'Password changed.');
            redirect("index.php?page={$role}_profile");
        }
    }
    $user = $userModel->findById($userId);
}

$pageTitle = 'My Profile';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/profile.php'; // reuse same view — it's generic
require __DIR__ . '/../../view/shared/footer.php';
