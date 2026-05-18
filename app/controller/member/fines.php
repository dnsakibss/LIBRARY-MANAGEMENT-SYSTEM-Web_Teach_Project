<?php
// app/controller/member/fines.php
requireLogin('member');
require_once __DIR__ . '/../../model/Models.php';

$memberId  = $_SESSION['user_id'];
$fineModel = new FineModel($conn);
$fines     = $fineModel->getByMember($memberId);

$pageTitle = 'My Fines';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/fines.php';
require __DIR__ . '/../../view/shared/footer.php';
