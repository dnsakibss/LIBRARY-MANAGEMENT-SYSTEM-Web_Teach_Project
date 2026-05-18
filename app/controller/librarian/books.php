<?php 
// app/controller/librarian/books.php

// Ensure only librarian users can access this page
requireLogin('librarian');

// Load required models for book operations
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

// Getting search input from URL (if any)
// Using trim to avoid unnecessary spaces issues
$search    = trim($_GET['search'] ?? '');

// Initialize BookModel with DB connection
$bookModel = new BookModel($conn);

// Fetch books based on search query (or all if empty)
$books     = $bookModel->getAll($search);

// Page title used in header view
$pageTitle = 'Book Catalog';

// Load layout components (header → main view → footer)
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/books.php';
require __DIR__ . '/../../view/shared/footer.php';