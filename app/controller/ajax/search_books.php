<?php
// app/controller/ajax/search_books.php
requireLogin();
require_once __DIR__ . '/../../model/BookModel.php';
header('Content-Type: application/json');
$q         = trim($_GET['q'] ?? '');
$bookModel = new BookModel($conn);
$books     = $q ? $bookModel->getAll($q) : [];
$slim = array_map(fn($b) => ['id'=>$b['id'],'title'=>$b['title'],'author'=>$b['author'],'isbn'=>$b['isbn']], $books);
echo json_encode(['success'=>true,'books'=>$slim]);
