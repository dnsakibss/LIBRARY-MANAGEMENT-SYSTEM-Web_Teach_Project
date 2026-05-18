<?php
// app/model/BorrowModel.php

class BorrowModel {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    private function baseSelect(): string {
        return "SELECT br.*,
                    u.name AS member_name, u.email AS member_email, u.phone AS member_phone,
                    b.title AS book_title, b.author AS book_author, b.isbn,
                    bn.name AS branch_name,
                    lib.name AS librarian_name
                FROM borrow_records br
                JOIN users u    ON br.member_id = u.id
                JOIN books b    ON br.book_id   = b.id
                JOIN branches bn ON br.branch_id = bn.id
                LEFT JOIN users lib ON br.librarian_id = lib.id";
    }

    public function createRequest(int $memberId, int $bookId, int $branchId): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO borrow_records (member_id, book_id, branch_id, status) VALUES (?,?,?,'pending')");
        $stmt->bind_param('iii', $memberId, $bookId, $branchId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function approve(int $id, int $librarianId, int $maxDays): bool {
        $borrow = date('Y-m-d');
        $due    = date('Y-m-d', strtotime("+{$maxDays} days"));
        $stmt   = $this->db->prepare(
            "UPDATE borrow_records SET status='active', librarian_id=?, borrow_date=?, due_date=? WHERE id=?");
        $stmt->bind_param('issi', $librarianId, $borrow, $due, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function reject(int $id, int $librarianId): bool {
        $stmt = $this->db->prepare(
            "UPDATE borrow_records SET status='rejected', librarian_id=? WHERE id=?");
        $stmt->bind_param('ii', $librarianId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function returnBook(int $id): bool {
        $today = date('Y-m-d');
        $stmt  = $this->db->prepare(
            "UPDATE borrow_records SET status='returned', return_date=? WHERE id=?");
        $stmt->bind_param('si', $today, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function renew(int $id, int $extraDays): bool {
        $stmt = $this->db->prepare(
            "UPDATE borrow_records SET due_date = DATE_ADD(due_date, INTERVAL ? DAY),
             renewals_count = renewals_count + 1 WHERE id=?");
        $stmt->bind_param('ii', $extraDays, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getById(int $id): ?array {
        $sql  = $this->baseSelect() . " WHERE br.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function getByMember(int $memberId, string $status = ''): array {
        $sql = $this->baseSelect() . " WHERE br.member_id = ?";
        if ($status) $sql .= " AND br.status = '$status'";
        $sql  .= " ORDER BY br.created_at DESC";
        $stmt  = $this->db->prepare($sql);
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getByBranch(int $branchId, string $status = '', string $filter = ''): array {
        $sql = $this->baseSelect() . " WHERE br.branch_id = ?";
        if ($status) $sql .= " AND br.status = '$status'";
        if ($filter === 'overdue')    $sql .= " AND br.due_date < CURDATE() AND br.status='active'";
        if ($filter === 'due_today')  $sql .= " AND br.due_date = CURDATE() AND br.status='active'";
        if ($filter === 'due_week')   $sql .= " AND br.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND br.status='active'";
        $sql  .= " ORDER BY br.created_at DESC";
        $stmt  = $this->db->prepare($sql);
        $stmt->bind_param('i', $branchId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getAllPending(): array {
        $sql = $this->baseSelect() . " WHERE br.status = 'pending' ORDER BY br.created_at ASC";
        $rows = $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        return $rows;
    }

    public function getAllActive(): array {
        $sql  = $this->baseSelect() . " WHERE br.status = 'active' ORDER BY br.due_date";
        $rows = $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        return $rows;
    }

    public function getAllOverdue(): array {
        $sql  = $this->baseSelect() . " WHERE br.status = 'active' AND br.due_date < CURDATE() ORDER BY br.due_date";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function totalActive(): int {
        $r = $this->db->query("SELECT COUNT(*) FROM borrow_records WHERE status='active'");
        return (int)$r->fetch_row()[0];
    }

    public function totalOverdue(): int {
        $r = $this->db->query("SELECT COUNT(*) FROM borrow_records WHERE status='active' AND due_date < CURDATE()");
        return (int)$r->fetch_row()[0];
    }

    public function monthlyStats(): array {
        $r = $this->db->query(
            "SELECT DATE_FORMAT(borrow_date,'%Y-%m') AS month, COUNT(*) AS total
             FROM borrow_records WHERE borrow_date IS NOT NULL
             GROUP BY month ORDER BY month DESC LIMIT 12");
        return $r->fetch_all(MYSQLI_ASSOC);
    }

    public function statsPerBranch(): array {
        $r = $this->db->query(
            "SELECT br.name AS branch_name,
                COUNT(CASE WHEN rec.status='active' THEN 1 END) AS active_loans,
                COUNT(CASE WHEN rec.status='active' AND rec.due_date < CURDATE() THEN 1 END) AS overdue_loans
             FROM branches br
             LEFT JOIN borrow_records rec ON rec.branch_id = br.id
             GROUP BY br.id ORDER BY br.name");
        return $r->fetch_all(MYSQLI_ASSOC);
    }

    public function monthlyReportByBranch(int $branchId): array {
        $stmt = $this->db->prepare(
            "SELECT DATE_FORMAT(borrow_date,'%Y-%m') AS month,
                COUNT(*) AS borrows,
                SUM(CASE WHEN status='returned' THEN 1 ELSE 0 END) AS returns
             FROM borrow_records WHERE branch_id=? AND borrow_date IS NOT NULL
             GROUP BY month ORDER BY month DESC LIMIT 12");
        $stmt->bind_param('i', $branchId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function hasActiveBorrow(int $memberId, int $bookId, int $branchId): bool {
        $stmt = $this->db->prepare(
            "SELECT id FROM borrow_records WHERE member_id=? AND book_id=? AND branch_id=?
             AND status IN ('pending','active') LIMIT 1");
        $stmt->bind_param('iii', $memberId, $bookId, $branchId);
        $stmt->execute();
        $stmt->store_result();
        $has = $stmt->num_rows > 0;
        $stmt->close();
        return $has;
    }

    public function countActiveForMember(int $memberId, int $branchId): int {
        // Count both active AND pending — pending requests consume a borrow slot
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM borrow_records WHERE member_id=? AND branch_id=? AND status IN ('active','pending')");
        $stmt->bind_param('ii', $memberId, $branchId);
        $stmt->execute();
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->close();
        return (int)$cnt;
    }
}
