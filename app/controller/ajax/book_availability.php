<?php
// app/controller/ajax/book_availability.php
// AJAX — returns JSON availability per branch for a book
requireLogin();
require_once __DIR__ . '/../../model/BookModel.php';
header('Content-Type: application/json');
$bookId    = (int)($_GET['book_id'] ?? 0);
$bookModel = new BookModel($conn);
$inventory = $bookModel->getWithInventory($bookId);
echo json_encode(['success' => true, 'inventory' => $inventory]);
