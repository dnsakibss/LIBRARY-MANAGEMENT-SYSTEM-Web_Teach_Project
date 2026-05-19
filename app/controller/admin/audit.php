<?php
/* app/controller/admin/audit.php */

// 1. Security Check: Ensure only logged-in administrators can access this log
requireLogin('admin');

// 2. Build the SQL Query step-by-step using string concatenation
// This query combines recent book borrow actions and fine payments into one unified history list
$sqlQuery = "SELECT 'borrow' AS type, u.name AS actor, b.title AS subject, ";
$sqlQuery .= "rec.status, rec.created_at AS action_time ";
$sqlQuery .= "FROM borrow_records rec ";
$sqlQuery .= "JOIN users u ON rec.member_id = u.id ";
$sqlQuery .= "JOIN books b ON rec.book_id = b.id ";

$sqlQuery .= "UNION ALL ";

$sqlQuery .= "SELECT 'fine' AS type, u.name AS actor, CONCAT('Fine ৳', f.amount) AS subject, ";
$sqlQuery .= "IF(f.is_paid, 'paid', 'unpaid') AS status, f.created_at AS action_time ";
$sqlQuery .= "FROM fines f ";
$sqlQuery .= "JOIN users u ON f.member_id = u.id ";

// Order everything from newest to oldest and cap it at the 100 most recent records
$sqlQuery .= "ORDER BY action_time DESC LIMIT 100";

// 3. Run the complete query against the database
$queryResult = $conn->query($sqlQuery);

// 4. Fetch all the resulting rows into an associative array for the view file
$auditLog = $queryResult->fetch_all(MYSQLI_ASSOC);

// 5. Define the page metadata title
$pageTitle = 'Audit Log';

// 6. Include the view templates to display the layout and data table
require __DIR__ . '/../../view/shared/header.php';
require __DIR__ . '/../../view/admin/audit.php';
require __DIR__ . '/../../view/shared/footer.php';