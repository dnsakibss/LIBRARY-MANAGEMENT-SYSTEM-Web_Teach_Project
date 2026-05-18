<?php
// app/controller/ajax/reading_list.php
// AJAX — toggle a book in/out of member reading list
requireLogin('member');
require_once __DIR__ . '/../../model/Models.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false]); exit; }
$bookId       = (int)($_POST['book_id'] ?? 0);
$memberId     = (int)$_SESSION['user_id'];
$readingModel = new ReadingListModel($conn);
$result       = $readingModel->toggle($memberId, $bookId);
echo json_encode(['success' => true, 'action' => $result]);
