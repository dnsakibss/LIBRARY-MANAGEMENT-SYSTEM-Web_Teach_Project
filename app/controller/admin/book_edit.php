<?php
/* app/controller/admin/book_edit.php */

// 1. Security Check: Protect the route so only logged-in admins can access it
requireLogin('admin');

// 2. Include the required database model files
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

// 3. Get the Book ID from the URL query parameters
$bookId = (int)($_GET['id'] ?? 0);

// 4. Initialize the Book Model and check if the book actually exists in the database
$bookModel = new BookModel($conn);
$book      = $bookModel->getById($bookId);

if ($book === null) {
    // If the book isn't found, set an alert message and send the admin back to the catalog list
    setFlash('danger', 'Book not found.');
    redirect('index.php?page=admin_books');
}

// 5. Initialize secondary models and pull data for the category/location dropdowns
$genreModel  = new GenreModel($conn);
$branchModel = new BranchModel($conn);
$genres      = $genreModel->getAll();
$branches    = $branchModel->getAll();

// 6. Fetch the current inventory quantities for this book across all branches
$rawInventory = $bookModel->getWithInventory($bookId);
$currentInventory = [];

foreach ($rawInventory as $inv) {
    // Map the records into an associative array using the branch ID as the key for fast lookups
    $currentInventory[$inv['branch_id']] = $inv;
}

// Array to accumulate validation error messages
$errors = [];

// 7. Handle Form Submission (When the admin clicks the update button)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitize and read text inputs from the form fields
    $title     = trim($_POST['title'] ?? '');
    $author    = trim($_POST['author'] ?? '');
    $isbn      = trim($_POST['isbn'] ?? '');
    $genreId   = (int)($_POST['genre_id'] ?? 0);
    $publisher = trim($_POST['publisher'] ?? '');
    $year      = (int)($_POST['published_year'] ?? 0);
    $desc      = trim($_POST['description'] ?? '');
    
    // Keep the existing cover image path as a default fallback
    $coverPath = $book['cover_image_path'];
    
    // Read the updated branch copies array from the form inputs
    $inventoryCopies = $_POST['inv_copies'] ?? [];

    // Form Validation: Make sure fields are not empty strings
    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    if ($author === '') {
        $errors[] = 'Author is required.';
    }
    if ($isbn === '') {
        $errors[] = 'ISBN is required.';
    }

    // 8. If validation passes, update data in the database
    if (empty($errors)) {
        
        // Handle alternative file upload for the book cover image
        if (!empty($_FILES['cover_image']['name'])) {
            $ext   = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $fname = 'cover_' . time() . '_' . rand(100, 999) . '.' . $ext;
            
            // Confirm directory safety structure
            if (!is_dir(UPLOAD_DIR . 'covers/')) {
                mkdir(UPLOAD_DIR . 'covers/', 0755, true);
            }
            
            // Move file out of temporary storage
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], UPLOAD_DIR . 'covers/' . $fname)) {
                $coverPath = 'uploads/covers/' . $fname;
            }
        }
        
        // Format blank database entries to NULL fields cleanly
        $finalGenreId = null;
        if ($genreId > 0) {
            $finalGenreId = $genreId;
        }
        
        $finalYear = null;
        if ($year > 0) {
            $finalYear = $year;
        }

        // Package data fields to update the book's information block
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
        
        // Execute the main text data update statement
        $bookModel->update($bookId, $data);

        // 9. Loop through inventory inputs to adjust stock allocations safely
        foreach ($inventoryCopies as $bid => $newTotal) {
            $newTotal = (int)$newTotal;
            $bid      = (int)$bid;
            
            if ($bid <= 0) {
                continue;
            }
            
            // Extract any existing inventory values stored for this specific branch
            $current   = $currentInventory[$bid] ?? null;
            $currTotal = 0;
            $currAvail = 0;
            
            if ($current !== null) {
                $currTotal = (int)$current['total_copies'];
                $currAvail = (int)$current['available_copies'];
            }
            
            // Critical calculation: Determine how many copies are currently out on loan
            $borrowed = $currTotal - $currAvail;
            
            if ($newTotal > 0) {
                // Prevent available count from dropping below zero using the max function
                $newAvail = max(0, $newTotal - $borrowed);
                
                // Add or update branch stock inventory rows
                $bookModel->upsertInventory($bookId, $bid, $newTotal, $newAvail);
                
            } else if ($newTotal === 0 && $current !== null && $borrowed === 0) {
                // If requested stock is zero and no books are currently borrowed out, wipe the record out safely
                $stmt = $conn->prepare("DELETE FROM branch_inventory WHERE book_id = ? AND branch_id = ?");
                $stmt->bind_param('ii', $bookId, $bid);
                $stmt->execute();
                $stmt->close();
            }
        }

        // 10. Direct the admin back to the book catalog with a success banner notice
        setFlash('success', "Book \"" . $title . "\" updated successfully.");
        redirect('index.php?page=admin_books');
    }
}

// 11. View Layer Construction
$pageTitle = 'Edit Book';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/book_edit.php';
require __DIR__ . '/../../view/shared/footer.php';