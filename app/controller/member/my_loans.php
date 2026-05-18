<?php
// app/controller/member/my_loans.php
requireLogin('member');
require_once __DIR__ . '/../../model/BorrowModel.php';

$memberId    = $_SESSION['user_id'];
$borrowModel = new BorrowModel($conn);

$activeLoans  = $borrowModel->getByMember($memberId, 'active');
$pendingLoans = $borrowModel->getByMember($memberId, 'pending');
$history      = $borrowModel->getByMember($memberId, 'returned');
$rejected     = $borrowModel->getByMember($memberId, 'rejected');

$pageTitle = 'My Loans';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/my_loans.php';
require __DIR__ . '/../../view/shared/footer.php';
