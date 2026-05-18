<?php
// app/controller/member/announcements.php
requireLogin('member');
require_once __DIR__ . '/../../model/Models.php';

$branchId      = (int)$_SESSION['branch_id'];
$announceModel = new AnnouncementModel($conn);
$announcements = $announceModel->getForMember($branchId);

$pageTitle = 'Announcements';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/announcements.php';
require __DIR__ . '/../../view/shared/footer.php';
