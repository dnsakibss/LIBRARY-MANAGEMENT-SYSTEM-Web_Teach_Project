<?php
// app/controller/member/reading_list.php
requireLogin('member');
require_once __DIR__ . '/../../model/Models.php';

$memberId     = $_SESSION['user_id'];
$readingModel = new ReadingListModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_book_id'])) {
    $readingModel->toggle($memberId, (int)$_POST['remove_book_id']);
    setFlash('success', 'Book removed from reading list.');
    redirect('index.php?page=member_reading_list');
}

$list      = $readingModel->getByMember($memberId);
$pageTitle = 'My Reading List';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/member/reading_list.php';
require __DIR__ . '/../../view/shared/footer.php';
