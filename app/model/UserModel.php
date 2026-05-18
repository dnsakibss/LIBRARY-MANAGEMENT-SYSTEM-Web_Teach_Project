<?php
// app/model/UserModel.php

class UserModel {
    private mysqli $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function getAllUsers(string $search = ''): array {
        if ($search) {
            $like = "%$search%";
            $stmt = $this->db->prepare(
                "SELECT u.*, b.name AS branch_name FROM users u
                 LEFT JOIN branches b ON u.branch_id = b.id
                 WHERE u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?
                 ORDER BY u.created_at DESC");
            $stmt->bind_param('sss', $like, $like, $like);
        } else {
            $stmt = $this->db->prepare(
                "SELECT u.*, b.name AS branch_name FROM users u
                 LEFT JOIN branches b ON u.branch_id = b.id
                 ORDER BY u.created_at DESC");
        }
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getUsersByRole(string $role): array {
        $stmt = $this->db->prepare(
            "SELECT u.*, b.name AS branch_name FROM users u
             LEFT JOIN branches b ON u.branch_id = b.id
             WHERE u.role = ? ORDER BY u.name");
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function register(string $name, string $email, string $phone, string $password, int $branchId): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password_hash, phone, role, branch_id) VALUES (?,?,?,?,'member',?)");
        $stmt->bind_param('ssssi', $name, $email, $hash, $phone, $branchId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function createStaff(array $d): bool {
        $hash = password_hash($d['password'], PASSWORD_BCRYPT);
        $branchId = !empty($d['branch_id']) ? (int)$d['branch_id'] : null;
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password_hash, phone, role, branch_id) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('sssssi', $d['name'], $d['email'], $hash, $d['phone'], $d['role'], $branchId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function updateProfile(int $id, array $d): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET name=?, phone=?, branch_id=? WHERE id=?");
        $branchId = !empty($d['branch_id']) ? (int)$d['branch_id'] : null;
        $stmt->bind_param('ssii', $d['name'], $d['phone'], $branchId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function updatePassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash=? WHERE id=?");
        $stmt->bind_param('si', $hash, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function updateProfilePic(int $id, string $path): bool {
        $stmt = $this->db->prepare("UPDATE users SET profile_pic=? WHERE id=?");
        $stmt->bind_param('si', $path, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function setActive(int $id, int $active): bool {
        $stmt = $this->db->prepare("UPDATE users SET is_active=? WHERE id=?");
        $stmt->bind_param('ii', $active, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function setRole(int $id, string $role): bool {
        $stmt = $this->db->prepare("UPDATE users SET role=? WHERE id=?");
        $stmt->bind_param('si', $role, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function updateFull(int $id, array $d): bool {
        $branchId = !empty($d['branch_id']) ? (int)$d['branch_id'] : null;
        $stmt = $this->db->prepare(
            "UPDATE users SET name=?, email=?, phone=?, role=?, branch_id=?, is_active=? WHERE id=?");
        $stmt->bind_param('ssssiis', $d['name'], $d['email'], $d['phone'], $d['role'], $branchId, $d['is_active'], $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function totalByRole(string $role): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role=?");
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->close();
        return (int)$cnt;
    }

    public function emailExists(string $email, int $excludeId = 0): bool {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email=? AND id != ? LIMIT 1");
        $stmt->bind_param('si', $email, $excludeId);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
}
