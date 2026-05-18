<?php /* app/controller/librarian/reservations.php */
requireLogin('librarian');
require_once __DIR__ . '/../../model/Models.php';
$branchId     = (int)$_SESSION['branch_id'];
$reserveModel = new ReservationModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reserveModel->fulfill((int)$_POST['reservation_id']);
    setFlash('success', 'Reservation marked as fulfilled. Member has been notified.');
    redirect('index.php?page=librarian_reservations');
}
$reservations = $reserveModel->getByBranch($branchId);
$pageTitle    = 'Reservation Waitlist';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/reservations.php';
require __DIR__ . '/../../view/shared/footer.php';
