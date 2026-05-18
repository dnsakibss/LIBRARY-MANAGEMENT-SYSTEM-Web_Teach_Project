<?php /* app/controller/admin/user_add.php */
requireLogin('admin');
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/Models.php';
$userModel   = new UserModel($conn);
$branchModel = new BranchModel($conn);
$branches    = $branchModel->getAll();
$errors = []; $old = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old  = $_POST;
    $name = trim($_POST['name'] ?? '');
    $email= trim($_POST['email'] ?? '');
    $phone= trim($_POST['phone'] ?? '');
    $role = $_POST['role'] ?? 'librarian';
    $pass = $_POST['password'] ?? '';
    if (!$name)  $errors[] = 'Name required.';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (strlen($pass) < 6) $errors[] = 'Password min 6 chars.';
    if ($userModel->emailExists($email)) $errors[] = 'Email already in use.';
    if (empty($errors)) {
        $userModel->createStaff(['name'=>$name,'email'=>$email,'phone'=>$phone,'role'=>$role,'password'=>$pass,'branch_id'=>(int)($_POST['branch_id']??0)]);
        setFlash('success', 'Staff account created.');
        redirect('index.php?page=admin_users');
    }
}
$pageTitle = 'Create Staff Account';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/user_add.php';
require __DIR__ . '/../../view/shared/footer.php';
