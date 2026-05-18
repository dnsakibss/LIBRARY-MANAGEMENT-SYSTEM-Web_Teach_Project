<?php 
/* app/controller/librarian/reservations.php */

// Restrict access to librarian users only
requireLogin('librarian');

require_once __DIR__ . '/../../model/Models.php';

// Current branch context
$branchId     = (int)$_SESSION['branch_id'];

$reserveModel = new ReservationModel($conn);

// Handle reservation fulfillment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mark reservation as fulfilled/processed
    $reserveModel->fulfill((int)$_POST['reservation_id']);

    // Success notification
    setFlash(
        'success',
        'Reservation marked as fulfilled. Member has been notified.'
    );

    // Redirect to prevent duplicate form submission
    redirect('index.php?page=librarian_reservations');
}

// Fetch waitlist entries for this branch
$reservations = $reserveModel->getByBranch($branchId);

// Page title for layout/view
$pageTitle    = 'Reservation Waitlist';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/reservations.php';
require __DIR__ . '/../../view/shared/footer.php';