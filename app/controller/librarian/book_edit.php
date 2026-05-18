<?php 
/* app/controller/librarian/book_edit.php */

// Restrict access to librarian users only
requireLogin('librarian');

require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

// Get selected book ID from URL
$bookId      = (int)($_GET['id'] ?? 0);

$bookModel   = new BookModel($conn);
$book        = $bookModel->getById($bookId);

// Stop execution if book does not exist
if (!$book) { 
    setFlash('danger', 'Book not found.'); 
    redirect('index.php?page=librarian_books'); 
}

// Load helper models for dropdown data
$genreModel  = new GenreModel($conn);
$branchModel = new BranchModel($conn);

$genres      = $genreModel->getAll();
$branches    = $branchModel->getAll();

// Current inventory state per branch
$currentInventory = [];
foreach ($bookModel->getWithInventory($bookId) as $inv) {
    $currentInventory[$inv['branch_id']] = $inv;
}

$errors = [];

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Form values
    $title     = trim($_POST['title'] ?? '');
    $author    = trim($_POST['author'] ?? '');
    $isbn      = trim($_POST['isbn'] ?? '');
    $genreId   = (int)($_POST['genre_id'] ?? 0);
    $publisher = trim($_POST['publisher'] ?? '');
    $year      = (int)($_POST['published_year'] ?? 0);
    $desc      = trim($_POST['description'] ?? '');

    // Keep old image unless a new one is uploaded
    $coverPath = $book['cover_image_path'];

    // Inventory input from form
    $inventoryCopies = $_POST['inv_copies'] ?? [];

    // Basic validation
    if (!$title)  $errors[] = 'Title is required.';
    if (!$author) $errors[] = 'Author is required.';
    if (!$isbn)   $errors[] = 'ISBN is required.';

    if (empty($errors)) {

        // Upload new cover image if user selected one
        if (!empty($_FILES['cover_image']['name'])) {

            $ext   = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);

            // Randomized filename to avoid collisions
            $fname = 'cover_' . time() . '_' . rand(100,999) . '.' . $ext;

            // Create covers directory if missing
            if (!is_dir(UPLOAD_DIR . 'covers/')) {
                mkdir(UPLOAD_DIR . 'covers/', 0755, true);
            }

            if (move_uploaded_file(
                $_FILES['cover_image']['tmp_name'], 
                UPLOAD_DIR . 'covers/' . $fname
            )) {
                $coverPath = 'uploads/covers/' . $fname;
            }
        }

        // Data payload for update query
        $data = [
            'title'            => $title,
            'author'           => $author,
            'isbn'             => $isbn,
            'genre_id'         => $genreId ?: null,
            'publisher'        => $publisher,
            'published_year'   => $year ?: null,
            'description'      => $desc,
            'cover_image_path' => $coverPath,
        ];

        // Update book details
        $bookModel->update($bookId, $data);

        // Inventory update logic
        // We preserve borrowed book counts while adjusting totals
        foreach ($inventoryCopies as $bid => $newTotal) {

            $newTotal = (int)$newTotal;
            $bid      = (int)$bid;

            if ($bid <= 0) continue;

            $current     = $currentInventory[$bid] ?? null;

            $currTotal   = $current ? (int)$current['total_copies'] : 0;
            $currAvail   = $current ? (int)$current['available_copies'] : 0;

            // Books currently borrowed from this branch
            $borrowed    = $currTotal - $currAvail;

            if ($newTotal > 0) {

                // New available copies after accounting for borrowed books
                $newAvail = max(0, $newTotal - $borrowed);

                $bookModel->upsertInventory(
                    $bookId, 
                    $bid, 
                    $newTotal, 
                    $newAvail
                );

            } elseif ($newTotal === 0 && $current) {

                // Remove inventory row only if no active borrowed copies exist
                if ($borrowed === 0) {

                    $stmt = $conn->prepare(
                        "DELETE FROM branch_inventory WHERE book_id=? AND branch_id=?"
                    );

                    $stmt->bind_param('ii', $bookId, $bid);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        // Success feedback
        setFlash('success', "Book updated successfully.");

        // Redirect back to catalog
        redirect('index.php?page=librarian_books');
    }
}

// Page title used by shared header
$pageTitle = 'Edit Book';

// Render views
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/book_edit.php';
require __DIR__ . '/../../view/shared/footer.php';