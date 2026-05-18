<?php /* app/controller/branch_manager/members_report.php */
requireLogin('branch_manager');
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/Models.php';
// Most active members
$r1 = $conn->query("SELECT u.name, u.email, br.name AS branch_name, COUNT(rec.id) AS borrow_count
    FROM users u JOIN branches br ON u.branch_id=br.id
    LEFT JOIN borrow_records rec ON rec.member_id=u.id
    WHERE u.role='member' GROUP BY u.id ORDER BY borrow_count DESC LIMIT 15");
$activeMembers = $r1->fetch_all(MYSQLI_ASSOC);

// Members with outstanding fines
$r2 = $conn->query("SELECT u.name, u.email, br.name AS branch_name, SUM(f.amount) AS total_fines
    FROM fines f JOIN users u ON f.member_id=u.id JOIN branches br ON u.branch_id=br.id
    WHERE f.is_paid=0 GROUP BY u.id ORDER BY total_fines DESC LIMIT 15");
$membersWithFines = $r2->fetch_all(MYSQLI_ASSOC);

// New registrations per branch
$r3 = $conn->query("SELECT b.name AS branch_name, COUNT(u.id) AS count
    FROM branches b LEFT JOIN users u ON u.branch_id=b.id AND u.role='member'
    GROUP BY b.id ORDER BY b.name");
$regPerBranch = $r3->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Member Activity Reports';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/branch_manager/members_report.php';
require __DIR__ . '/../../view/shared/footer.php';
