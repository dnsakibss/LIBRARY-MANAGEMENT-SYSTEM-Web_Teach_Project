<?php /* app/controller/librarian/genres.php */
requireLogin('librarian');
require_once __DIR__ . '/../../model/Models.php';
$genreModel = new GenreModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        if ($name) { $genreModel->add($name); setFlash('success','Genre added.'); }
    } elseif ($action === 'rename') {
        $genreModel->rename((int)$_POST['id'], trim($_POST['name'] ?? ''));
        setFlash('success','Genre renamed.');
    } elseif ($action === 'delete') {
        if (!$genreModel->delete((int)$_POST['id']))
            setFlash('danger','Cannot delete genre — books are assigned to it.');
        else setFlash('success','Genre deleted.');
    }
    redirect('index.php?page=librarian_genres');
}
$genres = $genreModel->getAll();
$pageTitle = 'Manage Genres';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/genres.php';
require __DIR__ . '/../../view/shared/footer.php';
