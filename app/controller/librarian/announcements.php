<?php 
/* app/controller/librarian/announcements.php */

// Only librarians can manage announcements
requireLogin('librarian');

require_once __DIR__ . '/../../model/Models.php';

// Current branch context
$branchId      = (int)$_SESSION['branch_id'];

$announceModel = new AnnouncementModel($conn);

// Handle new announcement submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $body  = trim($_POST['body'] ?? '');

    // Basic validation (avoid empty posts)
    if ($title && $body) {

        // Create announcement for this branch
        $announceModel->post(
            (int)$_SESSION['user_id'],
            $title,
            $body,
            $branchId
        );

        setFlash('success','Announcement posted.');
    }

    // Redirect to prevent resubmission on refresh
    redirect('index.php?page=librarian_announce');
}

// Load announcements visible to this branch
$announcements = $announceModel->getForMember($branchId);

// Page title for UI
$pageTitle = 'Announcements';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/announcements.php';
require __DIR__ . '/../../view/shared/footer.php';