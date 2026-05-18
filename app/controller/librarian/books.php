<?php
// app/controller/librarian/books.php
requireLogin('librarian');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

$search    = trim($_GET['search'] ?? '');
$bookModel = new BookModel($conn);
$books     = $bookModel->getAll($search);

$pageTitle = 'Book Catalog';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/librarian/books.php';
require __DIR__ . '/../../view/shared/footer.php';
