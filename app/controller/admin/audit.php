<?php /* app/controller/admin/audit.php */
requireLogin('admin');
// Audit log = recent borrow actions + fine actions
$r = $conn->query(
    "SELECT 'borrow' AS type, u.name AS actor, b.title AS subject,
            rec.status, rec.created_at AS action_time
     FROM borrow_records rec JOIN users u ON rec.member_id=u.id JOIN books b ON rec.book_id=b.id
     UNION ALL
     SELECT 'fine' AS type, u.name AS actor, CONCAT('Fine ৳',f.amount) AS subject,
            IF(f.is_paid,'paid','unpaid') AS status, f.created_at AS action_time
     FROM fines f JOIN users u ON f.member_id=u.id
     ORDER BY action_time DESC LIMIT 100");
$auditLog  = $r->fetch_all(MYSQLI_ASSOC);
$pageTitle = 'Audit Log';
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/audit.php';
require __DIR__ . '/../../view/shared/footer.php';
