<?php
// app/controller/member/dashboard.php
requireLogin('member');
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';
require_once __DIR__ . '/../../model/BorrowModel.php';

$memberId = $_SESSION['user_id'];
$branchId = (int)$_SESSION['branch_id'];

$borrowModel      = new BorrowModel($conn);
$fineModel        = new FineModel($conn);
$reservationModel = new ReservationModel($conn);
$announceModel    = new AnnouncementModel($conn);

$activeLoans   = $borrowModel->getByMember($memberId, 'active');
$pendingLoans  = $borrowModel->getByMember($memberId, 'pending');
$fines         = $fineModel->getByMember($memberId);
$unpaidFines   = array_filter($fines, fn($f) => !$f['is_paid']);
$reservations  = $reservationModel->getByMember($memberId);
$announcements = $announceModel->getForMember($branchId);

// Pass borrow limit info to view
$branchModel  = new BranchModel($conn);
$branchPolicy = $branchModel->getPolicy($branchId);
$maxBooks     = (int)$branchPolicy['max_books_per_member'];

$pageTitle = 'Member Dashboard';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/dashboard.php';
require __DIR__ . '/../../view/shared/footer.php';
