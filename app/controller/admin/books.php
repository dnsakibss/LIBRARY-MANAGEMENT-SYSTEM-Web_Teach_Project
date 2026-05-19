<?php
/* app/controller/admin/books.php */

// 1. Security Check: Protect the route so only logged-in admins can access it
requireLogin('admin');

// 2. Include the database models needed for catalog lookup and item removal
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

// 3. Initialize the Book Model using the global database connection variable ($conn)
$bookModel = new BookModel($conn);

// 4. Action Execution: Handle single book deletion requests submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the specific book delete identifier was passed in the request
    if (isset($_POST['delete_id'])) {
        
        // Extract the target book ID and safely convert it to an integer
        $targetBookId = (int)$_POST['delete_id'];
        
        // Execute the deletion operation inside the books table
        $bookModel->delete($targetBookId);
        
        // Save an alert flash banner confirmation message
        setFlash('success', 'Book deleted successfully.');
        
        // Redirect back to the same page to update the table grid and prevent form re-submits
        redirect('index.php?page=admin_books');
    }
}

// 5. Query Handling: Check if a search filtering keyword was typed into the search bar
$searchKeyword = '';
if (isset($_GET['search'])) {
    $searchKeyword = trim($_GET['search']);
}

// 6. Data Fetching: Retrieve books matching the keyword filter (or all books if blank)
$books = $bookModel->getAll($searchKeyword);

// 7. Define the page structural title metadata
$pageTitle = 'Global Book Catalog';

// 8. Assemble and layout the user interface templates
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/books.php';
require __DIR__ . '/../../view/shared/footer.php';