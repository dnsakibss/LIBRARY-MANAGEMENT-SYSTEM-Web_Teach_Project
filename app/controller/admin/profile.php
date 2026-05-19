<?php
/* app/controller/librarian/profile.php */

// 1. Determine the user's access role directly from their active session
$role = $_SESSION['role'];

// 2. Security Check: Protect the route using the dynamic role value
requireLogin($role);

// 3. Include the database model file needed for user profile modifications
require_once __DIR__ . '/../../model/UserModel.php';

// 4. Extract account details for the currently logged-in user
$userId    = $_SESSION['user_id'];
$userModel = new UserModel($conn);
$user      = $userModel->findById($userId);

// Array to track user input validation error messages
$errors = [];

// 5. Handle Form Actions (When the user clicks either of the update buttons)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check which form action identifier was submitted
    $action = $_POST['action'] ?? '';
    
    // ACTION A: Handle textual information updates (Name, Phone, and Profile Picture)
    if ($action === 'update_profile') {
        
        // Extract inputs from the form fields
        $inputName  = trim($_POST['name'] ?? '');
        $inputPhone = trim($_POST['phone'] ?? '');
        
        // Retain the current branch assignment unchanged
        $currentBranchId = $user['branch_id'];
        
        // Organize parameters clearly into a clean data array
        $data = [
            'name'      => $inputName,
            'phone'     => $inputPhone,
            'branch_id' => $currentBranchId
        ];
        
        // Validation: Verify that the name field isn't empty
        if ($inputName === '') {
            $errors[] = 'Name is required.';
        }
        
        // Execute updates if validation tests pass successfully
        if (empty($errors)) {
            
            // Check if a new profile avatar image file is selected
            if (!empty($_FILES['profile_pic']['name'])) {
                $ext   = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                
                // Build a structured, clear unique filename format
                $fname = 'profile_' . $userId . '_' . time() . '.' . $ext;
                $uploadDestination = UPLOAD_DIR . 'profiles/' . $fname;
                
                // Move file out of temporary server storage
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadDestination)) {
                    $dbImagePath = 'uploads/profiles/' . $fname;
                    $userModel->updateProfilePic($userId, $dbImagePath);
                }
            }
            
            // Save updated textual profile values into the database
            $userModel->updateProfile($userId, $data);
            
            // Refresh the current session name value immediately to update the navbar view
            $_SESSION['user_name'] = $inputName;
            
            // Set success flash notice and redirect safely
            setFlash('success', 'Profile updated.');
            
            $redirectUrl = 'index.php?page=' . $role . '_profile';
            redirect($redirectUrl);
        }
    }
    
    // ACTION B: Handle password security modification updates
    if ($action === 'change_password') {
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation: Verify old password matches hash stored in the record
        $storedHash = $user['password_hash'];
        if (password_verify($currentPassword, $storedHash) === false) {
            $errors[] = 'Current password incorrect.';
        }
        
        // Validation: Enforce secure length standard minimum boundary
        if (strlen($newPassword) < 6) {
            $errors[] = 'New password must be at least 6 characters.';
        }
        
        // Validation: Verify inputs match identically
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }
        
        // Save the updated credential hash if tests pass completely
        if (empty($errors)) {
            $userModel->updatePassword($userId, $newPassword);
            
            setFlash('success', 'Password changed.');
            
            $redirectUrl = 'index.php?page=' . $role . '_profile';
            redirect($redirectUrl);
        }
    }
    
    // Fetch refreshed information from the database to present accurate data post-action
    $user = $userModel->findById($userId);
}

// 6. Define layout structural variables and require view templates
$pageTitle = 'My Profile';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/profile.php'; 
require __DIR__ . '/../../view/shared/footer.php';