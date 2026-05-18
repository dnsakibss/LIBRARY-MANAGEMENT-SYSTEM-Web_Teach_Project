<?php /* app/controller/admin/books.php */
requireLogin('admin');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';
$search    = trim($_GET['search'] ?? '');
$bookModel = new BookModel($conn);
$books     = $bookModel->getAll($search);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $bookModel->delete((int)$_POST['delete_id']);
    setFlash('success', 'Book deleted.');
    redirect('index.php?page=admin_books');
}
$pageTitle = 'Global Book Catalog';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/books.php';
require __DIR__ . '/../../view/shared/footer.php';
