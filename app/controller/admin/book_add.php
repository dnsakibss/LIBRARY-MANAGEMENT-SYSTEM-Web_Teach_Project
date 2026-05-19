<?php
/* app/controller/admin/book_add.php */

requireLogin('admin');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

$bookModel   = new BookModel($conn);
$genreModel  = new GenreModel($conn);
$branchModel = new BranchModel($conn);

$genres   = $genreModel->getAll();
$branches = $branchModel->getAll();
$errors   = [];
$old      = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old       = $_POST;
    $title     = trim($_POST['title'] ?? '');
    $author    = trim($_POST['author'] ?? '');
    $isbn      = trim($_POST['isbn'] ?? '');
    $genreId   = (int)($_POST['genre_id'] ?? 0);
    $publisher = trim($_POST['publisher'] ?? '');
    $year      = (int)($_POST['published_year'] ?? 0);
    $desc      = trim($_POST['description'] ?? '');
    $coverPath = '';

    $inventoryCopies = $_POST['inv_copies'] ?? [];

    // Block: Text inputs and mandatory data fields validation
    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    if ($author === '') {
        $errors[] = 'Author is required.';
    }
    if ($isbn === '') {
        $errors[] = 'ISBN is required.';
    }

    // Block: Physical stock distribution validation
    $hasInventory = false;
    foreach ($inventoryCopies as $branchId => $quantity) {
        if ((int)$quantity > 0) {
            $hasInventory = true;
            break; 
        }
    }
    if ($hasInventory === false) {
        $errors[] = 'Please set copies for at least one branch.';
    }

    // Block: File upload and record persistence tracking
    if (empty($errors)) {
        if (!empty($_FILES['cover_image']['name'])) {
            $ext   = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $fname = 'cover_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $dest  = UPLOAD_DIR . 'covers/' . $fname;
            
            if (!is_dir(UPLOAD_DIR . 'covers/')) {
                mkdir(UPLOAD_DIR . 'covers/', 0755, true);
            }
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $dest)) {
                $coverPath = 'uploads/covers/' . $fname;
            }
        }
        
        $finalGenreId = null;
        if ($genreId > 0) {
            $finalGenreId = $genreId;
        }
        
        $finalYear = null;
        if ($year > 0) {
            $finalYear = $year;
        }

        $data = [
            'title'            => $title,
            'author'           => $author,
            'isbn'             => $isbn,
            'genre_id'         => $finalGenreId,
            'publisher'        => $publisher,
            'published_year'   => $finalYear,
            'description'      => $desc,
            'cover_image_path' => $coverPath,
        ];
        
        if ($bookModel->add($data)) {
            $newBookId = $conn->insert_id;
            
            foreach ($inventoryCopies as $bid => $qty) {
                $qty = (int)$qty;
                $bid = (int)$bid;
                if ($qty > 0 && $bid > 0) {
                    $bookModel->upsertInventory($newBookId, $bid, $qty, $qty);
                }
            }
            
            setFlash('success', "Book \"" . $title . "\" added successfully.");
            redirect('index.php?page=admin_books');
        } else {
            $errors[] = 'Failed to add book. ISBN may already exist.';
        }
    }
}

$pageTitle = 'Add New Book';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/book_add.php';
require __DIR__ . '/../../view/shared/footer.php';