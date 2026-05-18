<?php /* app/controller/admin/announcements.php */
requireLogin('admin');
require_once __DIR__ . '/../../model/Models.php';
$announceModel = new AnnouncementModel($conn);
$branchModel   = new BranchModel($conn);
$branches      = $branchModel->getAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title'] ?? '');
    $body     = trim($_POST['body'] ?? '');
    $branchId = ($_POST['scope'] === 'platform') ? null : (int)($_POST['branch_id'] ?? null);
    if ($title && $body) {
        $announceModel->post((int)$_SESSION['user_id'], $title, $body, $branchId);
        setFlash('success', 'Announcement posted.');
    }
    redirect('index.php?page=admin_announcements');
}
$announcements = $announceModel->getAll();
$pageTitle     = 'Manage Announcements';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/announcements.php';
require __DIR__ . '/../../view/shared/footer.php';
