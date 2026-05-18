<?php /* app/controller/branch_manager/announcements.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/Models.php';
$announceModel = new AnnouncementModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title     = trim($_POST['title'] ?? '');
    $body      = trim($_POST['body'] ?? '');
    $branchId  = ($_POST['scope'] === 'platform') ? null : (int)($_POST['branch_id'] ?? null);
    if ($title && $body) {
        $announceModel->post((int)$_SESSION['user_id'], $title, $body, $branchId);
        setFlash('success', 'Announcement posted.');
    }
    redirect('index.php?page=manager_announce');
}
$announcements = $announceModel->getAll();
$branchModel   = new BranchModel($conn);
$branches      = $branchModel->getAll();
$pageTitle     = 'Announcements';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/announcements.php';
require __DIR__ . '/../../view/shared/footer.php';
