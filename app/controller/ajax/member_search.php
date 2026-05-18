<?php
// app/controller/ajax/member_search.php
requireLogin('librarian','branch_manager','admin');
require_once __DIR__ . '/../../model/UserModel.php';
header('Content-Type: application/json');
$q         = trim($_GET['q'] ?? '');
$userModel = new UserModel($conn);
$members   = $q ? $userModel->getAllUsers($q) : [];
$members   = array_filter($members, fn($u) => $u['role'] === 'member');
$slim = array_map(fn($u) => ['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'phone'=>$u['phone']], $members);
echo json_encode(['success'=>true,'members'=>array_values($slim)]);
