<?php
// index.php — Front Controller (matches lab repo style: index.php routes via ?page=)

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

sessionStart();

$page = $_GET['page'] ?? 'home';

// ---- Page → controller map ----
$routes = [
    // Public
    'home'          => 'app/controller/auth/home.php',
    'login'         => 'app/controller/auth/login.php',
    'logout'        => 'app/controller/auth/logout.php',
    'register'      => 'app/controller/auth/register.php',
    'unauthorized'  => 'app/controller/auth/unauthorized.php',

    // Member
    'member_dashboard'      => 'app/controller/member/dashboard.php',
    'member_profile'        => 'app/controller/member/profile.php',
    'member_books'          => 'app/controller/member/browse_books.php',
    'member_book_detail'    => 'app/controller/member/book_detail.php',
    'member_borrow_request' => 'app/controller/member/borrow_request.php',
    'member_my_loans'       => 'app/controller/member/my_loans.php',
    'member_renew'          => 'app/controller/member/renew.php',
    'member_reservations'   => 'app/controller/member/reservations.php',
    'member_reading_list'   => 'app/controller/member/reading_list.php',
    'member_fines'          => 'app/controller/member/fines.php',
    'member_pay_fine'       => 'app/controller/member/pay_fine.php',
    'member_reviews'        => 'app/controller/member/reviews.php',
    'member_announcements'  => 'app/controller/member/announcements.php',

    // Librarian
    'librarian_dashboard'   => 'app/controller/librarian/dashboard.php',
    'librarian_books'       => 'app/controller/librarian/books.php',
    'librarian_book_add'    => 'app/controller/librarian/book_add.php',
    'librarian_book_edit'   => 'app/controller/librarian/book_edit.php',
    'librarian_genres'      => 'app/controller/librarian/genres.php',
    'librarian_inventory'   => 'app/controller/librarian/inventory.php',
    'librarian_requests'    => 'app/controller/librarian/borrow_requests.php',
    'librarian_returns'     => 'app/controller/librarian/returns.php',
    'librarian_loans'       => 'app/controller/librarian/active_loans.php',
    'librarian_fines'       => 'app/controller/librarian/fines.php',
    'librarian_members'     => 'app/controller/librarian/members.php',
    'librarian_reservations'=> 'app/controller/librarian/reservations.php',
    'librarian_stats'       => 'app/controller/librarian/stats.php',
    'librarian_announce'    => 'app/controller/librarian/announcements.php',
    'librarian_transfers'   => 'app/controller/librarian/transfers.php',

    // Branch Manager
    'manager_dashboard'     => 'app/controller/branch_manager/dashboard.php',
    'manager_branches'      => 'app/controller/branch_manager/branches.php',
    'manager_branch_add'    => 'app/controller/branch_manager/branch_add.php',
    'manager_branch_edit'   => 'app/controller/branch_manager/branch_edit.php',
    'manager_policies'      => 'app/controller/branch_manager/policies.php',
    'manager_librarians'    => 'app/controller/branch_manager/librarians.php',
    'manager_inventory'     => 'app/controller/branch_manager/inventory_report.php',
    'manager_stats'         => 'app/controller/branch_manager/stats.php',
    'manager_transfers'     => 'app/controller/branch_manager/transfers.php',
    'manager_announce'      => 'app/controller/branch_manager/announcements.php',
    'manager_reports'       => 'app/controller/branch_manager/reports.php',
    'manager_members_report'=> 'app/controller/branch_manager/members_report.php',

    // Admin
    'admin_dashboard'       => 'app/controller/admin/dashboard.php',
    'admin_users'           => 'app/controller/admin/users.php',
    'admin_user_add'        => 'app/controller/admin/user_add.php',
    'admin_user_edit'       => 'app/controller/admin/user_edit.php',
    'admin_branches'        => 'app/controller/admin/branches.php',
    'admin_books'           => 'app/controller/admin/books.php',
    'admin_book_add'        => 'app/controller/admin/book_add.php',
    'admin_book_edit'       => 'app/controller/admin/book_edit.php',
    'admin_settings'        => 'app/controller/admin/settings.php',
    'admin_transfers'       => 'app/controller/admin/transfers.php',
    'admin_reports'         => 'app/controller/admin/reports.php',
    'admin_announcements'   => 'app/controller/admin/announcements.php',
    'admin_audit'          => 'app/controller/admin/audit.php',

    // Profile pages for staff
    'librarian_profile'   => 'app/controller/librarian/profile.php',
    'manager_profile'     => 'app/controller/branch_manager/profile.php',
    'admin_profile'       => 'app/controller/admin/profile.php',

    // AJAX endpoints
    'ajax_book_availability' => 'app/controller/ajax/book_availability.php',
    'ajax_reading_list'      => 'app/controller/ajax/reading_list.php',
    'ajax_search_books'      => 'app/controller/ajax/search_books.php',
    'ajax_member_search'     => 'app/controller/ajax/member_search.php',
];

$file = isset($routes[$page]) ? __DIR__ . '/' . $routes[$page] : null;

if ($file && file_exists($file)) {
    require_once $file;
} else {
    http_response_code(404);
    require_once __DIR__ . '/app/view/shared/404.php';
}
