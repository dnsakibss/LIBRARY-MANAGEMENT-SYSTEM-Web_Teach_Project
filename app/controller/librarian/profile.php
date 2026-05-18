<?php
// app/controller/librarian/profile.php  (also used for manager + admin via symlink pattern)

// Role is dynamically determined from session (same file reused for multiple roles)
$role = $_SESSION['role'];

// Ensure user is authenticated for their current role
requireLogin($role);

require_once __DIR__ . '/../../model/UserModel.php';

// Logged-in user context
$userId    = $_SESSION['user_id'];

$userModel = new UserModel($conn);

// Load current user data
$user      = $userModel->findById($userId);

// Collect validation errors here
$errors    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // Update profile information
    if ($action === 'update_profile') {

        $data = [
            'name'      => trim($_POST['name'] ?? ''),
            'phone'     => trim($_POST['phone'] ?? ''),
            'branch_id' => $user['branch_id'] // branch stays unchanged
        ];

        // Basic validation
        if (!$data['name']) $errors[] = 'Name is required.';

        if (empty($errors)) {

            // Handle profile image upload (if provided)
            if (!empty($_FILES['profile_pic']['name'])) {

                $ext   = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);

                // Unique filename per user
                $fname = 'profile_' . $userId . '_' . time() . '.' . $ext;

                // Store file in profiles directory
                if (move_uploaded_file(
                    $_FILES['profile_pic']['tmp_name'],
                    UPLOAD_DIR . 'profiles/' . $fname
                )) {
                    $userModel->updateProfilePic(
                        $userId,
                        'uploads/profiles/' . $fname
                    );
                }
            }

            // Update main profile info
            $userModel->updateProfile($userId, $data);

            // Update session name so UI reflects immediately
            $_SESSION['user_name'] = $data['name'];

            setFlash('success', 'Profile updated.');

            // Redirect back to role-based profile page
            redirect("index.php?page={$role}_profile");
        }

    } elseif ($action === 'change_password') {

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // Verify current password first
        if (!password_verify($current, $user['password_hash']))
            $errors[] = 'Current password incorrect.';

        // Basic password rules
        if (strlen($new) < 6)
            $errors[] = 'New password must be at least 6 characters.';

        // Ensure confirmation matches
        if ($new !== $confirm)
            $errors[] = 'Passwords do not match.';

        if (empty($errors)) {

            // Update password securely
            $userModel->updatePassword($userId, $new);

            setFlash('success', 'Password changed.');

            redirect("index.php?page={$role}_profile");
        }
    }

    // Reload updated user data after any operation
    $user = $userModel->findById($userId);
}

// Page title for UI
$pageTitle = 'My Profile';

require __DIR__ . '/../../view/shared/header.php';

// Reusing member view since it's generic across roles
require __DIR__ . '/../../view/member/profile.php';

require __DIR__ . '/../../view/shared/footer.php';