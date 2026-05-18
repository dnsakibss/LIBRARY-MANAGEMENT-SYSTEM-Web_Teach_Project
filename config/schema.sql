-- ============================================================
--  Library Management System — Full Schema
--  Run: mysql -u root -p < config/schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS library_ms
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE library_ms;

-- ---- users ----
CREATE TABLE IF NOT EXISTS users (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150)  NOT NULL,
    email        VARCHAR(150)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone        VARCHAR(25)   DEFAULT NULL,
    role         ENUM('member','librarian','branch_manager','admin') NOT NULL DEFAULT 'member',
    profile_pic  VARCHAR(255)  DEFAULT NULL,
    branch_id    INT           DEFAULT NULL,
    is_active    TINYINT(1)    NOT NULL DEFAULT 1,
    created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---- branches ----
CREATE TABLE IF NOT EXISTS branches (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(150) NOT NULL,
    address    TEXT         DEFAULT NULL,
    city       VARCHAR(100) DEFAULT NULL,
    phone      VARCHAR(25)  DEFAULT NULL,
    manager_id INT          DEFAULT NULL,
    is_active  TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---- branch_policies ----
CREATE TABLE IF NOT EXISTS branch_policies (
    id                    INT AUTO_INCREMENT PRIMARY KEY,
    branch_id             INT NOT NULL UNIQUE,
    max_borrow_days       INT NOT NULL DEFAULT 14,
    max_books_per_member  INT NOT NULL DEFAULT 5,
    fine_rate_per_day     DECIMAL(6,2) NOT NULL DEFAULT 5.00,
    max_renewals          INT NOT NULL DEFAULT 2,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---- genres ----
CREATE TABLE IF NOT EXISTS genres (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ---- books ----
CREATE TABLE IF NOT EXISTS books (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    title            VARCHAR(255) NOT NULL,
    author           VARCHAR(150) NOT NULL,
    isbn             VARCHAR(25)  NOT NULL UNIQUE,
    genre_id         INT          DEFAULT NULL,
    publisher        VARCHAR(150) DEFAULT NULL,
    published_year   YEAR         DEFAULT NULL,
    description      TEXT         DEFAULT NULL,
    cover_image_path VARCHAR(255) DEFAULT NULL,
    created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---- branch_inventory ----
CREATE TABLE IF NOT EXISTS branch_inventory (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    book_id          INT NOT NULL,
    branch_id        INT NOT NULL,
    total_copies     INT NOT NULL DEFAULT 0,
    available_copies INT NOT NULL DEFAULT 0,
    UNIQUE KEY uq_book_branch (book_id, branch_id),
    FOREIGN KEY (book_id)   REFERENCES books(id)    ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---- borrow_records ----
CREATE TABLE IF NOT EXISTS borrow_records (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    member_id       INT NOT NULL,
    book_id         INT NOT NULL,
    branch_id       INT NOT NULL,
    librarian_id    INT DEFAULT NULL,
    status          ENUM('pending','active','returned','rejected') NOT NULL DEFAULT 'pending',
    borrow_date     DATE DEFAULT NULL,
    due_date        DATE DEFAULT NULL,
    return_date     DATE DEFAULT NULL,
    renewals_count  INT NOT NULL DEFAULT 0,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (book_id)      REFERENCES books(id)    ON DELETE CASCADE,
    FOREIGN KEY (branch_id)    REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (librarian_id) REFERENCES users(id)    ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---- reservations ----
CREATE TABLE IF NOT EXISTS reservations (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    member_id   INT NOT NULL,
    book_id     INT NOT NULL,
    branch_id   INT NOT NULL,
    reserved_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status      ENUM('waiting','fulfilled','cancelled') NOT NULL DEFAULT 'waiting',
    FOREIGN KEY (member_id) REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (book_id)   REFERENCES books(id)    ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---- fines ----
CREATE TABLE IF NOT EXISTS fines (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    borrow_record_id INT NOT NULL,
    member_id        INT NOT NULL,
    branch_id        INT NOT NULL,
    amount           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    reason           VARCHAR(255)  NOT NULL DEFAULT 'Overdue',
    is_paid          TINYINT(1)    NOT NULL DEFAULT 0,
    paid_at          TIMESTAMP     NULL DEFAULT NULL,
    created_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (borrow_record_id) REFERENCES borrow_records(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id)        REFERENCES users(id)          ON DELETE CASCADE,
    FOREIGN KEY (branch_id)        REFERENCES branches(id)       ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---- book_reviews ----
CREATE TABLE IF NOT EXISTS book_reviews (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    book_id     INT NOT NULL,
    member_id   INT NOT NULL,
    rating      TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review_text TEXT    DEFAULT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_member_book (member_id, book_id),
    FOREIGN KEY (book_id)   REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---- announcements ----
CREATE TABLE IF NOT EXISTS announcements (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    branch_id    INT          DEFAULT NULL,
    author_id    INT          NOT NULL,
    title        VARCHAR(255) NOT NULL,
    body         TEXT         NOT NULL,
    published_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id)    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---- inter_branch_requests ----
CREATE TABLE IF NOT EXISTS inter_branch_requests (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    book_id        INT NOT NULL,
    from_branch_id INT NOT NULL,
    to_branch_id   INT NOT NULL,
    requested_by   INT NOT NULL,
    status         ENUM('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id)        REFERENCES books(id)    ON DELETE CASCADE,
    FOREIGN KEY (from_branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (to_branch_id)   REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_by)   REFERENCES users(id)    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---- reading_lists ----
CREATE TABLE IF NOT EXISTS reading_lists (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    book_id   INT NOT NULL,
    added_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_member_book_rl (member_id, book_id),
    FOREIGN KEY (member_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id)   REFERENCES books(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Seed Data
-- ============================================================

INSERT INTO genres (name) VALUES
  ('Fiction'), ('Science'), ('History'), ('Technology'),
  ('Mathematics'), ('Literature'), ('Philosophy'), ('Biography'), ('Self-Help');

INSERT INTO branches (name, address, city, phone, is_active) VALUES
  ('Central Branch',  '12 Library Road',   'Dhaka',      '01700000001', 1),
  ('North Branch',    '45 University Ave',  'Chittagong', '01700000002', 1),
  ('South Branch',    '78 College Street',  'Sylhet',     '01700000003', 1);

INSERT INTO branch_policies (branch_id, max_borrow_days, max_books_per_member, fine_rate_per_day, max_renewals)
VALUES (1,14,5,5.00,2), (2,10,4,7.00,1), (3,21,6,3.00,3);

-- Admin user (password: admin123)
INSERT INTO users (name, email, password_hash, phone, role, branch_id, is_active)
VALUES ('System Admin', 'admin@library.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, 1);

-- Branch Manager (password: manager123)
INSERT INTO users (name, email, password_hash, phone, role, branch_id, is_active)
VALUES ('Rahim Manager', 'manager@library.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01711000010', 'branch_manager', 1, 1);

UPDATE branches SET manager_id = 2 WHERE id = 1;

-- Librarian (password: librarian123)
INSERT INTO users (name, email, password_hash, phone, role, branch_id, is_active)
VALUES ('Karim Librarian', 'librarian@library.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01711000020', 'librarian', 1, 1);

-- Member (password: member123)
INSERT INTO users (name, email, password_hash, phone, role, branch_id, is_active)
VALUES ('Alice Member', 'member@library.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01711000030', 'member', 1, 1);

INSERT INTO books (title, author, isbn, genre_id, publisher, published_year, description) VALUES
  ('The Great Gatsby',         'F. Scott Fitzgerald', '9780743273565', 1, 'Scribner',       1925, 'A classic American novel.'),
  ('A Brief History of Time',  'Stephen Hawking',     '9780553380163', 2, 'Bantam Books',   1988, 'Cosmology for the masses.'),
  ('Sapiens',                  'Yuval Noah Harari',   '9780062316097', 3, 'Harper Collins', 2011, 'History of humankind.'),
  ('Clean Code',               'Robert C. Martin',    '9780132350884', 4, 'Prentice Hall',  2008, 'Writing clean software.'),
  ('1984',                     'George Orwell',       '9780451524935', 1, 'Signet Classic',  1949, 'A dystopian masterpiece.'),
  ('Atomic Habits',            'James Clear',         '9780735211292', 9, 'Avery',          2018, 'Build good habits.');

INSERT INTO branch_inventory (book_id, branch_id, total_copies, available_copies) VALUES
  (1,1,3,3),(2,1,2,2),(3,1,4,4),(4,1,2,2),(5,1,5,5),(6,1,3,3),
  (1,2,2,2),(3,2,3,3),(5,2,2,2),
  (2,3,1,1),(4,3,2,2),(6,3,4,4);

-- ============================================================
-- TROUBLESHOOTING: If librarian cannot see borrow requests,
-- run this query to check their branch assignment:
-- SELECT id, name, email, role, branch_id FROM users WHERE role='librarian';
-- 
-- To fix a librarian with no branch:
-- UPDATE users SET branch_id = 1 WHERE email = 'librarian@library.com';
-- (replace 1 with the correct branch id)
-- ============================================================
