<?php
// app/controller/member/browse_books.php
requireLogin('member');
require_once __DIR__ . '/../../model/BookModel.php';
require_once __DIR__ . '/../../model/Models.php';

$search  = trim($_GET['search'] ?? '');
$genreId = (int)($_GET['genre_id'] ?? 0);

$bookModel  = new BookModel($conn);
$genreModel = new GenreModel($conn);

$books  = $bookModel->getAll($search, $genreId);
$genres = $genreModel->getAll();

$pageTitle = 'Browse Books';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/browse_books.php';
require __DIR__ . '/../../view/shared/footer.php';
