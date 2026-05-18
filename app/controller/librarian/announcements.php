<?php /* app/controller/librarian/announcements.php */
requireLogin('librarian');
require_once __DIR__ . '/../../model/Models.php';
$branchId      = (int)$_SESSION['branch_id'];
$announceModel = new AnnouncementModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body  = trim($_POST['body'] ?? '');
    if ($title && $body) {
        $announceModel->post((int)$_SESSION['user_id'], $title, $body, $branchId);
        setFlash('success','Announcement posted.');
    }
    redirect('index.php?page=librarian_announce');
}
$announcements = $announceModel->getForMember($branchId);
$pageTitle = 'Announcements';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/announcements.php';
require __DIR__ . '/../../view/shared/footer.php';
