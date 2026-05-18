<?php
// app/model/FineModel.php

class FineModel {
    private mysqli $db;
    public function __construct(mysqli $db) { $this->db = $db; }

    public function createOverdueFine(int $recordId, int $memberId, int $branchId, float $amount): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO fines (borrow_record_id, member_id, branch_id, amount, reason)
             VALUES (?,?,?,'Overdue return') ON DUPLICATE KEY UPDATE amount=?");
        $stmt->bind_param('iiid', $recordId, $memberId, $branchId, $amount);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function createManual(int $recordId, int $memberId, int $branchId, float $amount, string $reason): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO fines (borrow_record_id, member_id, branch_id, amount, reason) VALUES (?,?,?,?,?)");
        $stmt->bind_param('iiids', $recordId, $memberId, $branchId, $amount, $reason);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getByMember(int $memberId): array {
        $stmt = $this->db->prepare(
            "SELECT f.*, b.title AS book_title, br.name AS branch_name
             FROM fines f
             JOIN borrow_records rec ON f.borrow_record_id = rec.id
             JOIN books b ON rec.book_id = b.id
             JOIN branches br ON f.branch_id = br.id
             WHERE f.member_id = ? ORDER BY f.created_at DESC");
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getByBranch(int $branchId): array {
        $stmt = $this->db->prepare(
            "SELECT f.*, u.name AS member_name, u.email AS member_email,
                    b.title AS book_title
             FROM fines f
             JOIN users u ON f.member_id = u.id
             JOIN borrow_records rec ON f.borrow_record_id = rec.id
             JOIN books b ON rec.book_id = b.id
             WHERE f.branch_id = ? ORDER BY f.created_at DESC");
        $stmt->bind_param('i', $branchId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function markPaid(int $fineId): bool {
        $now  = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("UPDATE fines SET is_paid=1, paid_at=? WHERE id=?");
        $stmt->bind_param('si', $now, $fineId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function memberRequestPay(int $fineId, int $memberId): bool {
        // Member signals intent — mark with a note (we use reason column prefix)
        $stmt = $this->db->prepare(
            "UPDATE fines SET reason = CONCAT('[PAYMENT REQUESTED] ', reason) WHERE id=? AND member_id=? AND is_paid=0");
        $stmt->bind_param('ii', $fineId, $memberId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function totalOutstanding(): float {
        $r = $this->db->query("SELECT SUM(amount) FROM fines WHERE is_paid=0");
        return (float)$r->fetch_row()[0];
    }

    public function monthlyCollected(): array {
        $r = $this->db->query(
            "SELECT DATE_FORMAT(paid_at,'%Y-%m') AS month, SUM(amount) AS total
             FROM fines WHERE is_paid=1 AND paid_at IS NOT NULL
             GROUP BY month ORDER BY month DESC LIMIT 12");
        return $r->fetch_all(MYSQLI_ASSOC);
    }

    public function outstandingPerBranch(): array {
        $r = $this->db->query(
            "SELECT br.name AS branch_name, SUM(f.amount) AS total
             FROM fines f JOIN branches br ON f.branch_id = br.id
             WHERE f.is_paid=0 GROUP BY br.id ORDER BY br.name");
        return $r->fetch_all(MYSQLI_ASSOC);
    }
}

// ---------------------------------------------------------------------------

class ReservationModel {
    private mysqli $db;
    public function __construct(mysqli $db) { $this->db = $db; }

    public function reserve(int $memberId, int $bookId, int $branchId): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO reservations (member_id, book_id, branch_id) VALUES (?,?,?)");
        $stmt->bind_param('iii', $memberId, $bookId, $branchId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function cancel(int $id, int $memberId): bool {
        $stmt = $this->db->prepare(
            "UPDATE reservations SET status='cancelled' WHERE id=? AND member_id=?");
        $stmt->bind_param('ii', $id, $memberId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function fulfill(int $id): bool {
        $stmt = $this->db->prepare("UPDATE reservations SET status='fulfilled' WHERE id=?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getByMember(int $memberId): array {
        $stmt = $this->db->prepare(
            "SELECT r.*, b.title AS book_title, b.author, br.name AS branch_name,
                (SELECT COUNT(*) FROM reservations r2
                 WHERE r2.book_id=r.book_id AND r2.branch_id=r.branch_id
                 AND r2.status='waiting' AND r2.id <= r.id) AS queue_position
             FROM reservations r
             JOIN books b ON r.book_id = b.id
             JOIN branches br ON r.branch_id = br.id
             WHERE r.member_id=? ORDER BY r.reserved_at DESC");
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getByBranch(int $branchId): array {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.name AS member_name, u.email AS member_email,
                    b.title AS book_title
             FROM reservations r
             JOIN users u ON r.member_id = u.id
             JOIN books b ON r.book_id = b.id
             WHERE r.branch_id=? AND r.status='waiting'
             ORDER BY r.reserved_at ASC");
        $stmt->bind_param('i', $branchId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function hasReservation(int $memberId, int $bookId, int $branchId): bool {
        $stmt = $this->db->prepare(
            "SELECT id FROM reservations WHERE member_id=? AND book_id=? AND branch_id=? AND status='waiting' LIMIT 1");
        $stmt->bind_param('iii', $memberId, $bookId, $branchId);
        $stmt->execute();
        $stmt->store_result();
        $has = $stmt->num_rows > 0;
        $stmt->close();
        return $has;
    }

    public function countWaiting(int $bookId, int $branchId): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM reservations WHERE book_id=? AND branch_id=? AND status='waiting'");
        $stmt->bind_param('ii', $bookId, $branchId);
        $stmt->execute();
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->close();
        return (int)$cnt;
    }
}

// ---------------------------------------------------------------------------

class BranchModel {
    private mysqli $db;
    public function __construct(mysqli $db) { $this->db = $db; }

    public function getAll(): array {
        $r = $this->db->query(
            "SELECT br.*, u.name AS manager_name,
                (SELECT COUNT(*) FROM users WHERE branch_id=br.id AND role='librarian') AS librarian_count
             FROM branches br LEFT JOIN users u ON br.manager_id = u.id ORDER BY br.name");
        return $r->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT br.*, u.name AS manager_name FROM branches br
             LEFT JOIN users u ON br.manager_id = u.id WHERE br.id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function add(array $d): int {
        $stmt = $this->db->prepare(
            "INSERT INTO branches (name, address, city, phone) VALUES (?,?,?,?)");
        $stmt->bind_param('ssss', $d['name'], $d['address'], $d['city'], $d['phone']);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function update(int $id, array $d): bool {
        $stmt = $this->db->prepare(
            "UPDATE branches SET name=?, address=?, city=?, phone=?, manager_id=?, is_active=? WHERE id=?");
        $managerId = !empty($d['manager_id']) ? (int)$d['manager_id'] : null;
        $stmt->bind_param('ssssiis', $d['name'], $d['address'], $d['city'], $d['phone'], $managerId, $d['is_active'], $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function setActive(int $id, int $active): bool {
        $stmt = $this->db->prepare("UPDATE branches SET is_active=? WHERE id=?");
        $stmt->bind_param('ii', $active, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getPolicy(int $branchId): array {
        $stmt = $this->db->prepare("SELECT * FROM branch_policies WHERE branch_id=? LIMIT 1");
        $stmt->bind_param('i', $branchId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: [
            'max_borrow_days'      => DEFAULT_MAX_DAYS,
            'max_books_per_member' => DEFAULT_MAX_BOOKS,
            'fine_rate_per_day'    => DEFAULT_FINE_RATE,
            'max_renewals'         => DEFAULT_MAX_RENEWALS,
        ];
    }

    public function savePolicy(int $branchId, array $d): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO branch_policies (branch_id, max_borrow_days, max_books_per_member, fine_rate_per_day, max_renewals)
             VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE
             max_borrow_days=?, max_books_per_member=?, fine_rate_per_day=?, max_renewals=?");
        $stmt->bind_param('iiidiiid',
            $branchId, $d['max_borrow_days'], $d['max_books_per_member'], $d['fine_rate_per_day'], $d['max_renewals'],
            $d['max_borrow_days'], $d['max_books_per_member'], $d['fine_rate_per_day'], $d['max_renewals']);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

// ---------------------------------------------------------------------------

class GenreModel {
    private mysqli $db;
    public function __construct(mysqli $db) { $this->db = $db; }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM genres ORDER BY name")->fetch_all(MYSQLI_ASSOC);
    }

    public function add(string $name): bool {
        $stmt = $this->db->prepare("INSERT INTO genres (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function rename(int $id, string $name): bool {
        $stmt = $this->db->prepare("UPDATE genres SET name=? WHERE id=?");
        $stmt->bind_param('si', $name, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool {
        // Block if books use this genre
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM books WHERE genre_id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->close();
        if ($cnt > 0) return false;
        $stmt = $this->db->prepare("DELETE FROM genres WHERE id=?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

// ---------------------------------------------------------------------------

class ReviewModel {
    private mysqli $db;
    public function __construct(mysqli $db) { $this->db = $db; }

    public function getByBook(int $bookId): array {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.name AS member_name FROM book_reviews r
             JOIN users u ON r.member_id = u.id
             WHERE r.book_id=? ORDER BY r.created_at DESC");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getByMember(int $memberId): array {
        $stmt = $this->db->prepare(
            "SELECT r.*, b.title AS book_title FROM book_reviews r
             JOIN books b ON r.book_id = b.id
             WHERE r.member_id=? ORDER BY r.created_at DESC");
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function upsert(int $bookId, int $memberId, int $rating, string $text): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO book_reviews (book_id, member_id, rating, review_text)
             VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE rating=?, review_text=?");
        $stmt->bind_param('iiissi', $bookId, $memberId, $rating, $text, $rating, $text);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $bookId, int $memberId): bool {
        $stmt = $this->db->prepare("DELETE FROM book_reviews WHERE book_id=? AND member_id=?");
        $stmt->bind_param('ii', $bookId, $memberId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

// ---------------------------------------------------------------------------

class ReadingListModel {
    private mysqli $db;
    public function __construct(mysqli $db) { $this->db = $db; }

    public function getByMember(int $memberId): array {
        $stmt = $this->db->prepare(
            "SELECT rl.*, b.title, b.author, b.isbn, b.cover_image_path, g.name AS genre_name
             FROM reading_lists rl
             JOIN books b ON rl.book_id = b.id
             LEFT JOIN genres g ON b.genre_id = g.id
             WHERE rl.member_id=? ORDER BY rl.added_at DESC");
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function toggle(int $memberId, int $bookId): string {
        // Returns 'added' or 'removed'
        $stmt = $this->db->prepare(
            "SELECT id FROM reading_lists WHERE member_id=? AND book_id=? LIMIT 1");
        $stmt->bind_param('ii', $memberId, $bookId);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        if ($exists) {
            $stmt = $this->db->prepare("DELETE FROM reading_lists WHERE member_id=? AND book_id=?");
            $stmt->bind_param('ii', $memberId, $bookId);
            $stmt->execute();
            $stmt->close();
            return 'removed';
        } else {
            $stmt = $this->db->prepare("INSERT INTO reading_lists (member_id, book_id) VALUES (?,?)");
            $stmt->bind_param('ii', $memberId, $bookId);
            $stmt->execute();
            $stmt->close();
            return 'added';
        }
    }

    public function isInList(int $memberId, int $bookId): bool {
        $stmt = $this->db->prepare(
            "SELECT id FROM reading_lists WHERE member_id=? AND book_id=? LIMIT 1");
        $stmt->bind_param('ii', $memberId, $bookId);
        $stmt->execute();
        $stmt->store_result();
        $in = $stmt->num_rows > 0;
        $stmt->close();
        return $in;
    }
}

// ---------------------------------------------------------------------------

class AnnouncementModel {
    private mysqli $db;
    public function __construct(mysqli $db) { $this->db = $db; }

    public function getForMember(int $branchId): array {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.name AS author_name, b.name AS branch_name
             FROM announcements a
             JOIN users u ON a.author_id = u.id
             LEFT JOIN branches b ON a.branch_id = b.id
             WHERE a.branch_id = ? OR a.branch_id IS NULL
             ORDER BY a.published_at DESC LIMIT 30");
        $stmt->bind_param('i', $branchId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getAll(): array {
        $r = $this->db->query(
            "SELECT a.*, u.name AS author_name, b.name AS branch_name
             FROM announcements a
             JOIN users u ON a.author_id = u.id
             LEFT JOIN branches b ON a.branch_id = b.id
             ORDER BY a.published_at DESC");
        return $r->fetch_all(MYSQLI_ASSOC);
    }

    public function post(int $authorId, string $title, string $body, ?int $branchId): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO announcements (author_id, title, body, branch_id) VALUES (?,?,?,?)");
        $stmt->bind_param('issi', $authorId, $title, $body, $branchId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}

// ---------------------------------------------------------------------------

class TransferModel {
    private mysqli $db;
    public function __construct(mysqli $db) { $this->db = $db; }

    public function create(int $bookId, int $fromBranch, int $toBranch, int $requestedBy): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO inter_branch_requests (book_id, from_branch_id, to_branch_id, requested_by)
             VALUES (?,?,?,?)");
        $stmt->bind_param('iiii', $bookId, $fromBranch, $toBranch, $requestedBy);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE inter_branch_requests SET status=? WHERE id=?");
        $stmt->bind_param('si', $status, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getAll(): array {
        $r = $this->db->query(
            "SELECT t.*, b.title AS book_title,
                    f.name AS from_branch, to_b.name AS to_branch,
                    u.name AS requested_by_name
             FROM inter_branch_requests t
             JOIN books b ON t.book_id = b.id
             JOIN branches f ON t.from_branch_id = f.id
             JOIN branches to_b ON t.to_branch_id = to_b.id
             JOIN users u ON t.requested_by = u.id
             ORDER BY t.created_at DESC");
        return $r->fetch_all(MYSQLI_ASSOC);
    }

    public function getByBranch(int $branchId): array {
        $stmt = $this->db->prepare(
            "SELECT t.*, b.title AS book_title,
                    f.name AS from_branch, to_b.name AS to_branch,
                    u.name AS requested_by_name
             FROM inter_branch_requests t
             JOIN books b ON t.book_id = b.id
             JOIN branches f ON t.from_branch_id = f.id
             JOIN branches to_b ON t.to_branch_id = to_b.id
             JOIN users u ON t.requested_by = u.id
             WHERE t.from_branch_id=? OR t.to_branch_id=?
             ORDER BY t.created_at DESC");
        $stmt->bind_param('ii', $branchId, $branchId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}
