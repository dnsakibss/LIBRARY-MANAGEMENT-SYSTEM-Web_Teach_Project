<?php 
/* app/controller/librarian/members.php */

// Only librarians can access member records
requireLogin('librarian');

require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';

// Search input + selected member
$search    = trim($_GET['search'] ?? '');
$memberId  = (int)($_GET['member_id'] ?? 0);

$userModel = new UserModel($conn);

// Load all users with member role
$members   = $userModel->getUsersByRole('member');

// Simple client-side style filtering
if ($search) {

    $members = array_filter($members, fn($m) =>

        stripos($m['name'],  $search) !== false ||
        stripos($m['email'], $search) !== false ||
        stripos($m['phone'], $search) !== false
    );
}

// Default values for member details section
$memberDetail = null;
$memberLoans  = [];
$memberFines  = [];

// If a specific member is selected
if ($memberId) {

    // Fetch member profile info
    $memberDetail = $userModel->findById($memberId);

    $borrowModel  = new BorrowModel($conn);
    $fineModel    = new FineModel($conn);

    // Loan + fine history for this member
    $memberLoans  = $borrowModel->getByMember($memberId);
    $memberFines  = $fineModel->getByMember($memberId);
}

// Page title used by layout
$pageTitle = 'Member Records';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/members.php';
require __DIR__ . '/../../view/shared/footer.php';