<?php
/* app/controller/admin/announcements.php */

// 1. Security Check: Protect the route so only logged-in admins can access it
requireLogin('admin');

// 2. Include the database models needed for announcements and branch filtering
require_once __DIR__ . '/../../model/Models.php';

// 3. Initialize the model objects with the global database connection ($conn)
$announceModel = new AnnouncementModel($conn);
$branchModel   = new BranchModel($conn);

// 4. Fetch all available library branches to populate the scope selection dropdown in the form
$branches = $branchModel->getAll();

// 5. Handle Form Submission (When the admin clicks the submit button)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Clean user inputs by removing accidental whitespace from the edges
    $title = trim($_POST['title'] ?? '');
    $body  = trim($_POST['body'] ?? '');
    
    // Check the scope of the announcement (Platform-wide vs. Specific Branch)
    $scope = $_POST['scope'] ?? 'platform';
    
    if ($scope === 'platform') {
        // If it's platform-wide, it doesn't belong to a single branch (store as NULL)
        $branchId = null;
    } else {
        // If it's for a specific branch, read the branch ID and convert it safely to an integer
        $branchId = (int)($_POST['branch_id'] ?? 0);
    }
    
    // Form Validation: Ensure both a title and a body message have been provided
    if ($title !== '' && $body !== '') {
        // Get the current logged-in admin's user ID from the active session
        $adminUserId = (int)$_SESSION['user_id'];
        
        // Save the new announcement to the database
        $announceModel->post($adminUserId, $title, $body, $branchId);
        
        // Set a quick success message to notify the admin
        setFlash('success', 'Announcement posted successfully.');
    }
    
    // Refresh the page to clear form data and prevent accidental double-submits
    redirect('index.php?page=admin_announcements');
}

// 6. Fetch all historical announcements from the database to display them in a list/table
$announcements = $announceModel->getAll();

// 7. Define the page metadata title
$pageTitle = 'Manage Announcements';

// 8. Assemble and display the structural view layers
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/announcements.php';
require __DIR__ . '/../../view/shared/footer.php';