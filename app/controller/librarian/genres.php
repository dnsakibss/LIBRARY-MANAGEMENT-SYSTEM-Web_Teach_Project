<?php 
/* app/controller/librarian/genres.php */

// Restrict access to librarian only
requireLogin('librarian');

require_once __DIR__ . '/../../model/Models.php';

// Genre model handles all genre-related DB operations
$genreModel = new GenreModel($conn);

// Handle form actions (add / rename / delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // Add new genre
    if ($action === 'add') {

        $name = trim($_POST['name'] ?? '');

        if ($name) { 
            $genreModel->add($name);

            // Simple success feedback
            setFlash('success','Genre added.'); 
        }

    } elseif ($action === 'rename') {

        // Rename existing genre
        $genreModel->rename(
            (int)$_POST['id'], 
            trim($_POST['name'] ?? '')
        );

        setFlash('success','Genre renamed.');

    } elseif ($action === 'delete') {

        // Prevent deleting genres already linked with books
        if (!$genreModel->delete((int)$_POST['id']))
            setFlash('danger','Cannot delete genre — books are assigned to it.');
        else 
            setFlash('success','Genre deleted.');
    }

    // Refresh page after action
    redirect('index.php?page=librarian_genres');
}

// Fetch all genres for display
$genres = $genreModel->getAll();

// Page title used in shared layout
$pageTitle = 'Manage Genres';

require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/genres.php';
require __DIR__ . '/../../view/shared/footer.php';