<?php 
// app/controller/librarian/book_add.php

// Only librarians are allowed to access this functionality
requireLogin('librarian');

// Load required models for book, genre, and branch handling
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

// Model instances (DB connection already available in $conn)
$bookModel  = new BookModel($conn);
$genreModel = new GenreModel($conn);
$branchModel = new BranchModel($conn);

// Fetch dropdown data for form (genres + branches)
$genres     = $genreModel->getAll();
$branches   = $branchModel->getAll();

// Error container + old form data (useful after validation failure)
$errors     = [];
$old        = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Keep old input so form can be repopulated on error
    $old       = $_POST;

    // Basic book info
    $title     = trim($_POST['title'] ?? '');
    $author    = trim($_POST['author'] ?? '');
    $isbn      = trim($_POST['isbn'] ?? '');
    $genreId   = (int)($_POST['genre_id'] ?? 0);
    $publisher = trim($_POST['publisher'] ?? '');
    $year      = (int)($_POST['published_year'] ?? 0);
    $desc      = trim($_POST['description'] ?? '');

    // Default cover image path (empty if not uploaded)
    $coverPath = '';

    // Inventory inputs (branch-wise copies mapping)
    $inventoryBranches = $_POST['inv_branch'] ?? [];
    $inventoryCopies   = $_POST['inv_copies'] ?? [];

    // Basic validation checks
    if (!$title)  $errors[] = 'Title is required.';
    if (!$author) $errors[] = 'Author is required.';
    if (!$isbn)   $errors[] = 'ISBN is required.';

    // Ensure at least one branch has stock
    $hasInventory = false;
    foreach ($inventoryCopies as $bid => $qty) {
        if ((int)$qty > 0) { 
            $hasInventory = true; 
            break; 
        }
    }

    if (!$hasInventory) $errors[] = 'Please set copies for at least one branch.';

    // If no validation errors, proceed with DB insert
    if (empty($errors)) {

        // Handle cover image upload (if provided)
        if (!empty($_FILES['cover_image']['name'])) {

            $ext   = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);

            // Unique-ish filename to avoid overwriting
            $fname = 'cover_' . time() . '_' . rand(100,999) . '.' . $ext;

            $dest  = UPLOAD_DIR . 'covers/' . $fname;

            // Ensure upload directory exists
            if (!is_dir(UPLOAD_DIR . 'covers/')) {
                mkdir(UPLOAD_DIR . 'covers/', 0755, true);
            }

            // Move uploaded file to final destination
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $dest)) {
                $coverPath = 'uploads/covers/' . $fname;
            }
        }

        // Prepare book data for insertion
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

        // Insert book into database
        if ($bookModel->add($data)) {

            // Get auto-generated book ID
            $newBookId = $conn->insert_id;

            // Save inventory per branch (only where copies > 0)
            foreach ($inventoryCopies as $bid => $qty) {
                $qty = (int)$qty;
                $bid = (int)$bid;

                if ($qty > 0 && $bid > 0) {
                    $bookModel->upsertInventory($newBookId, $bid, $qty, $qty);
                }
            }

            // Success message for UI feedback
            setFlash('success', "Book \"{$title}\" added with inventory successfully.");

            // Redirect back to books page
            redirect('index.php?page=librarian_books');

        } else {
            // Likely duplicate ISBN or DB failure
            $errors[] = 'Failed to add book. ISBN may already exist.';
        }
    }
}

// Page title for view rendering
$pageTitle = 'Add Book';

// Load page layout
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/book_add.php';
require __DIR__ . '/../../view/shared/footer.php';