<?php
// app/controller/member/renew.php
requireLogin('member');
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

$recordId = (int)($_POST['record_id'] ?? 0);
$memberId = $_SESSION['user_id'];

$borrowModel = new BorrowModel($conn);
$branchModel = new BranchModel($conn);
$record      = $borrowModel->getById($recordId);

if (!$record || $record['member_id'] != $memberId || $record['status'] !== 'active') {
    setFlash('danger', 'Invalid loan record.');
    redirect('index.php?page=member_my_loans');
}

$policy      = $branchModel->getPolicy((int)$record['branch_id']);
$maxRenewals = $policy['max_renewals'];

if ($record['renewals_count'] >= $maxRenewals) {
    setFlash('danger', "You have used all {$maxRenewals} renewals for this loan.");
} else {
    $borrowModel->renew($recordId, (int)$policy['max_borrow_days']);
    setFlash('success', 'Loan renewed successfully.');
}
redirect('index.php?page=member_my_loans');
