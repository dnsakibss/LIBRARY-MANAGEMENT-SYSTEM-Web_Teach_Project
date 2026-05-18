<?php /* app/controller/branch_manager/librarians.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/Models.php';
$userModel   = new UserModel($conn);
$branchModel = new BranchModel($conn);
$branches    = $branchModel->getAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid      = (int)$_POST['user_id'];
    $branchId = (int)$_POST['branch_id'];
    $stmt = $conn->prepare("UPDATE users SET branch_id=? WHERE id=? AND role='librarian'");
    $stmt->bind_param('ii', $branchId, $uid);
    $stmt->execute(); $stmt->close();
    setFlash('success', 'Librarian assignment updated.');
    redirect('index.php?page=manager_librarians');
}
$librarians = $userModel->getUsersByRole('librarian');
$pageTitle  = 'Librarian Assignments';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/librarians.php';
require __DIR__ . '/../../view/shared/footer.php';
