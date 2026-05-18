<?php
// app/controller/member/pay_fine.php
requireLogin('member');
require_once __DIR__ . '/../../model/Models.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fineId    = (int)($_POST['fine_id'] ?? 0);
    $memberId  = $_SESSION['user_id'];
    $fineModel = new FineModel($conn);
    $fineModel->memberRequestPay($fineId, $memberId);
    setFlash('info', 'Payment request submitted. A librarian will confirm your payment shortly.');
}
redirect('index.php?page=member_fines');
