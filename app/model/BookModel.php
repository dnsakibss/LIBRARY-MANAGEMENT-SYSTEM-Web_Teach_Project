<?php
// app/model/BookModel.php

class BookModel {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    public function getAll(string $search = '', int $genreId = 0): array {
        $sql = "SELECT b.*, g.name AS genre_name FROM books b
                LEFT JOIN genres g ON b.genre_id = g.id WHERE 1=1";
        $params = [];
        $types  = '';

        if ($search) {
            $like = "%$search%";
            $sql .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
            $params = array_merge($params, [$like, $like, $like]);
            $types .= 'sss';
        }
        if ($genreId) {
            $sql .= " AND b.genre_id = ?";
            $params[] = $genreId;
            $types .= 'i';
        }
        $sql .= " ORDER BY b.title";

        $stmt = $this->db->prepare($sql);
        if ($params) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT b.*, g.name AS genre_name FROM books b
             LEFT JOIN genres g ON b.genre_id = g.id WHERE b.id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function getWithInventory(int $bookId): array {
        $stmt = $this->db->prepare(
            "SELECT bi.*, br.name AS branch_name, br.city
             FROM branch_inventory bi
             JOIN branches br ON bi.branch_id = br.id
             WHERE bi.book_id = ? ORDER BY br.name");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getAverageRating(int $bookId): float {
        $stmt = $this->db->prepare(
            "SELECT AVG(rating) AS avg_rating FROM book_reviews WHERE book_id = ?");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return round((float)($row['avg_rating'] ?? 0), 1);
    }

    public function add(array $d): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO books (title, author, isbn, genre_id, publisher, published_year, description, cover_image_path)
             VALUES (?,?,?,?,?,?,?,?)");
        $genreId = !empty($d['genre_id']) ? (int)$d['genre_id'] : null;
        $year    = !empty($d['published_year']) ? (int)$d['published_year'] : null;
        $stmt->bind_param('sssisiss', $d['title'], $d['author'], $d['isbn'],
            $genreId, $d['publisher'], $year, $d['description'], $d['cover_image_path']);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function update(int $id, array $d): bool {
        $stmt = $this->db->prepare(
            "UPDATE books SET title=?, author=?, isbn=?, genre_id=?, publisher=?,
             published_year=?, description=?, cover_image_path=? WHERE id=?");
        $genreId = !empty($d['genre_id']) ? (int)$d['genre_id'] : null;
        $year    = !empty($d['published_year']) ? (int)$d['published_year'] : null;
        $stmt->bind_param('sssisissi', $d['title'], $d['author'], $d['isbn'],
            $genreId, $d['publisher'], $year, $d['description'], $d['cover_image_path'], $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id=?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function totalCount(): int {
        $result = $this->db->query("SELECT COUNT(*) FROM books");
        return (int)$result->fetch_row()[0];
    }

    // Inventory
    public function getInventoryByBranch(int $branchId): array {
        $stmt = $this->db->prepare(
            "SELECT bi.*, b.title, b.author, b.isbn, g.name AS genre_name
             FROM branch_inventory bi
             JOIN books b ON bi.book_id = b.id
             LEFT JOIN genres g ON b.genre_id = g.id
             WHERE bi.branch_id = ? ORDER BY b.title");
        $stmt->bind_param('i', $branchId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function upsertInventory(int $bookId, int $branchId, int $total, int $available): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO branch_inventory (book_id, branch_id, total_copies, available_copies)
             VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE total_copies=?, available_copies=?");
        $stmt->bind_param('iiiiii', $bookId, $branchId, $total, $available, $total, $available);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function decrementCopies(int $bookId, int $branchId): bool {
        $stmt = $this->db->prepare(
            "UPDATE branch_inventory SET available_copies = available_copies - 1
             WHERE book_id=? AND branch_id=? AND available_copies > 0");
        $stmt->bind_param('ii', $bookId, $branchId);
        $stmt->execute();
        $affected = $stmt->affected_rows; // read BEFORE close()
        $stmt->close();
        return $affected > 0;
    }

    public function incrementCopies(int $bookId, int $branchId): bool {
        $stmt = $this->db->prepare(
            "UPDATE branch_inventory SET available_copies = available_copies + 1
             WHERE book_id=? AND branch_id=? AND available_copies < total_copies");
        $stmt->bind_param('ii', $bookId, $branchId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function availableCopies(int $bookId, int $branchId): int {
        $stmt = $this->db->prepare(
            "SELECT available_copies FROM branch_inventory WHERE book_id=? AND branch_id=?");
        $stmt->bind_param('ii', $bookId, $branchId);
        $stmt->execute();
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->close();
        return (int)$cnt;
    }

    // Cross-branch inventory report
    public function getCrossBranchInventory(): array {
        $result = $this->db->query(
            "SELECT b.title, b.author, br.name AS branch_name,
                    bi.total_copies, bi.available_copies
             FROM branch_inventory bi
             JOIN books b ON bi.book_id = b.id
             JOIN branches br ON bi.branch_id = br.id
             ORDER BY b.title, br.name");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMostBorrowedByBranch(int $branchId, int $limit = 10): array {
        $stmt = $this->db->prepare(
            "SELECT b.title, b.author, COUNT(br.id) AS borrow_count
             FROM borrow_records br
             JOIN books b ON br.book_id = b.id
             WHERE br.branch_id = ? AND br.status IN ('active','returned')
             GROUP BY b.id ORDER BY borrow_count DESC LIMIT ?");
        $stmt->bind_param('ii', $branchId, $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getNeverBorrowed(int $branchId): array {
        $stmt = $this->db->prepare(
            "SELECT b.title, b.author FROM branch_inventory bi
             JOIN books b ON bi.book_id = b.id
             WHERE bi.branch_id = ?
             AND b.id NOT IN (SELECT book_id FROM borrow_records WHERE branch_id = ?)");
        $stmt->bind_param('ii', $branchId, $branchId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}
