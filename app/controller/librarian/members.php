<?php /* app/controller/librarian/members.php */
requireLogin('librarian');
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/BorrowModel.php';
require_once __DIR__ . '/../../model/Models.php';
$search    = trim($_GET['search'] ?? '');
$memberId  = (int)($_GET['member_id'] ?? 0);
$userModel = new UserModel($conn);
$members   = $userModel->getUsersByRole('member');
if ($search) {
    $members = array_filter($members, fn($m) =>
        stripos($m['name'],$search)!==false || stripos($m['email'],$search)!==false || stripos($m['phone'],$search)!==false
    );
}
$memberDetail = null;
$memberLoans  = [];
$memberFines  = [];
if ($memberId) {
    $memberDetail = $userModel->findById($memberId);
    $borrowModel  = new BorrowModel($conn);
    $fineModel    = new FineModel($conn);
    $memberLoans  = $borrowModel->getByMember($memberId);
    $memberFines  = $fineModel->getByMember($memberId);
}
$pageTitle = 'Member Records';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/members.php';
require __DIR__ . '/../../view/shared/footer.php';
