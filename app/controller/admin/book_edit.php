<?php
/* app/controller/admin/book_edit.php */

requireLogin('admin');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

$bookId    = (int)($_GET['id'] ?? 0);
$bookModel = new BookModel($conn);
$book      = $bookModel->getById($bookId);

if ($book === null) {
    setFlash('danger', 'Book not found.');
    redirect('index.php?page=admin_books');
}

$genreModel  = new GenreModel($conn);
$branchModel = new BranchModel($conn);
$genres      = $genreModel->getAll();
$branches    = $branchModel->getAll();

$rawInventory     = $bookModel->getWithInventory($bookId);
$currentInventory = [];
foreach ($rawInventory as $inv) {
    $currentInventory[$inv['branch_id']] = $inv;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title     = trim($_POST['title'] ?? '');
    $author    = trim($_POST['author'] ?? '');
    $isbn      = trim($_POST['isbn'] ?? '');
    $genreId   = (int)($_POST['genre_id'] ?? 0);
    $publisher = trim($_POST['publisher'] ?? '');
    $year      = (int)($_POST['published_year'] ?? 0);
    $desc      = trim($_POST['description'] ?? '');
    $coverPath = $book['cover_image_path'];
    
    $inventoryCopies = $_POST['inv_copies'] ?? [];

    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    if ($author === '') {
        $errors[] = 'Author is required.';
    }
    if ($isbn === '') {
        $errors[] = 'ISBN is required.';
    }

    if (empty($errors)) {
        // Block: Optional replacement cover upload handling
        if (!empty($_FILES['cover_image']['name'])) {
            $ext   = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $fname = 'cover_' . time() . '_' . rand(100, 999) . '.' . $ext;
            
            if (!is_dir(UPLOAD_DIR . 'covers/')) {
                mkdir(UPLOAD_DIR . 'covers/', 0755, true);
            }
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], UPLOAD_DIR . 'covers/' . $fname)) {
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
        
        $bookModel->update($bookId, $data);

        // Block: Delta calculation for processing multi-branch inventory updates safely
        foreach ($inventoryCopies as $bid => $newTotal) {
            $newTotal = (int)$newTotal;
            $bid      = (int)$bid;
            
            if ($bid <= 0) {
                continue;
            }
            
            $current   = $currentInventory[$bid] ?? null;
            $currTotal = 0;
            $currAvail = 0;
            
            if ($current !== null) {
                $currTotal = (int)$current['total_copies'];
                $currAvail = (int)$current['available_copies'];
            }
            
            $borrowed = $currTotal - $currAvail;
            
            if ($newTotal > 0) {
                $newAvail = max(0, $newTotal - $borrowed);
                $bookModel->upsertInventory($bookId, $bid, $newTotal, $newAvail);
                
            } else if ($newTotal === 0 && $current !== null && $borrowed === 0) {
                $stmt = $conn->prepare("DELETE FROM branch_inventory WHERE book_id = ? AND branch_id = ?");
                $stmt->bind_param('ii', $bookId, $bid);
                $stmt->execute();
                $stmt->close();
            }
        }

        setFlash('success', "Book \"" . $title . "\" updated successfully.");
        redirect('index.php?page=admin_books');
    }
}

$pageTitle = 'Edit Book';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/book_edit.php';
require __DIR__ . '/../../view/shared/footer.php';