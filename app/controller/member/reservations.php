<?php
// app/controller/member/reservations.php
requireLogin('member');
require_once __DIR__ . '/../../model/Models.php';

$memberId     = $_SESSION['user_id'];
$reserveModel = new ReservationModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $reserveModel->cancel((int)$_POST['cancel_id'], $memberId);
    setFlash('success', 'Reservation cancelled.');
    redirect('index.php?page=member_reservations');
}

$reservations = $reserveModel->getByMember($memberId);

$pageTitle = 'My Reservations';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/reservations.php';
require __DIR__ . '/../../view/shared/footer.php';
