<?php
// config/app.php — global constants and helper functions

define('BASE_URL',   'http://localhost/lms/');
define('BASE_PATH',  dirname(__DIR__));
define('UPLOAD_DIR', BASE_PATH . '/public/uploads/');
define('APP_NAME',   'Library Management System');

// Global fine/borrow defaults (used if branch has no policy)
define('DEFAULT_FINE_RATE',    5.00);
define('DEFAULT_MAX_DAYS',     14);
define('DEFAULT_MAX_BOOKS',    5);
define('DEFAULT_MAX_RENEWALS', 2);

// ---- Session helper ----
function sessionStart(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
}

// ---- Auth guard ---- call at top of every protected controller
function requireLogin(string ...$roles): void {
    sessionStart();
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'index.php?page=login');
        exit;
    }
    if (!empty($roles) && !in_array($_SESSION['role'], $roles, true)) {
        header('Location: ' . BASE_URL . 'index.php?page=unauthorized');
        exit;
    }
}

// ---- Flash messages ----
function setFlash(string $type, string $msg): void {
    sessionStart();
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash(): ?array {
    sessionStart();
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

// ---- Sanitize ----
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// ---- Redirect ----
function redirect(string $path): never {
    header('Location: ' . BASE_URL . $path);
    exit;
}

// ---- Role dashboard redirect ----
function dashboardForRole(string $role): string {
    return match($role) {
        'member'         => 'index.php?page=member_dashboard',
        'librarian'      => 'index.php?page=librarian_dashboard',
        'branch_manager' => 'index.php?page=manager_dashboard',
        'admin'          => 'index.php?page=admin_dashboard',
        default          => 'index.php?page=login',
    };
}
