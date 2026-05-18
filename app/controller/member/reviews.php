<?php
// app/controller/member/reviews.php
requireLogin('member');
require_once __DIR__ . '/../../model/Models.php';

$memberId    = $_SESSION['user_id'];
$reviewModel = new ReviewModel($conn);
$reviews     = $reviewModel->getByMember($memberId);

$pageTitle = 'My Reviews';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/reviews.php';
require __DIR__ . '/../../view/shared/footer.php';
