<?php
// app/view/shared/header.php
// Variables expected: $pageTitle, $role (from controller)
$flash = getFlash();
$navRole = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($pageTitle ?? APP_NAME) ?> — <?= APP_NAME ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body>

<?php if (!empty($_SESSION['user_id'])): ?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm
    <?= match($navRole){
        'admin'          => 'bg-dark',
        'branch_manager' => 'bg-danger',
        'librarian'      => 'bg-success',
        default          => 'bg-primary'
    } ?>">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
      <i class="bi bi-book-half me-2"></i><?= APP_NAME ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">

        <?php if ($navRole === 'member'): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=member_dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=member_books"><i class="bi bi-search me-1"></i>Browse Books</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=member_my_loans"><i class="bi bi-arrow-left-right me-1"></i>My Loans</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=member_reservations"><i class="bi bi-clock me-1"></i>Reservations</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=member_reading_list"><i class="bi bi-bookmark-heart me-1"></i>Reading List</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=member_fines"><i class="bi bi-cash me-1"></i>Fines</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=member_announcements"><i class="bi bi-megaphone me-1"></i>Announcements</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=member_reviews"><i class="bi bi-star me-1"></i>My Reviews</a></li>

        <?php elseif ($navRole === 'librarian'): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=librarian_dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=librarian_books"><i class="bi bi-journals me-1"></i>Catalog</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=librarian_requests"><i class="bi bi-inbox me-1"></i>Requests</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=librarian_returns"><i class="bi bi-arrow-return-left me-1"></i>Returns</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=librarian_loans"><i class="bi bi-list-check me-1"></i>Loans</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=librarian_fines"><i class="bi bi-cash-coin me-1"></i>Fines</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=librarian_members"><i class="bi bi-people me-1"></i>Members</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=librarian_stats"><i class="bi bi-bar-chart me-1"></i>Stats</a></li>

        <?php elseif ($navRole === 'branch_manager'): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=manager_dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=manager_branches"><i class="bi bi-building me-1"></i>Branches</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=manager_policies"><i class="bi bi-gear me-1"></i>Policies</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=manager_librarians"><i class="bi bi-person-badge me-1"></i>Librarians</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=manager_stats"><i class="bi bi-bar-chart me-1"></i>Stats</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=manager_transfers"><i class="bi bi-arrow-left-right me-1"></i>Transfers</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=manager_reports"><i class="bi bi-file-earmark-text me-1"></i>Reports</a></li>

        <?php elseif ($navRole === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=admin_dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=admin_users"><i class="bi bi-people me-1"></i>Users</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=admin_branches"><i class="bi bi-building me-1"></i>Branches</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=admin_books"><i class="bi bi-journals me-1"></i>Books</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=admin_transfers"><i class="bi bi-arrow-left-right me-1"></i>Transfers</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=admin_reports"><i class="bi bi-graph-up me-1"></i>Reports</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=admin_settings"><i class="bi bi-sliders me-1"></i>Settings</a></li>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2">
          <span class="badge bg-light text-dark text-capitalize"><?= e($navRole) ?></span>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle me-1"></i><?= e($_SESSION['user_name'] ?? 'User') ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= BASE_URL ?>index.php?page=<?= $navRole ?>_profile"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>index.php?page=logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php endif; ?>

<main class="container-fluid px-4 py-3">

<?php if ($flash): ?>
<div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show mt-2" role="alert">
  <?= e($flash['msg']) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
