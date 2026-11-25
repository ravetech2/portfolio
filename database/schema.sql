-- Schema ng portfolio database
-- Paano gamitin: i-import ito sa phpMyAdmin (Import tab) o patakbuhin sa terminal:
--   mysql -u root -p < database/schema.sql

-- Gumawa ng database kung wala pa
CREATE DATABASE IF NOT EXISTS portfolio_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portfolio_db;

-- Talahanayan ng mga user (para sa login at sign up)
CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(50)  NOT NULL UNIQUE,
  email         VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,  -- naka-hash, hindi plain text
  role          ENUM('admin','user') NOT NULL DEFAULT 'user',  -- "admin" lang ang may access sa dashboard
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Talahanayan ng mga mensahe mula sa contact form
CREATE TABLE IF NOT EXISTS messages (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(120) NOT NULL,
  message    TEXT NOT NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Talahanayan ng mga proyekto (ipinapakita sa landing page at projects page)
CREATE TABLE IF NOT EXISTS projects (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title        VARCHAR(120) NOT NULL,
  description  TEXT NOT NULL,
  technologies VARCHAR(255) NOT NULL,          -- pinaghihiwalay ng kuwit, hal. "PHP,MySQL"
  icon         VARCHAR(50)  NOT NULL DEFAULT 'globe',  -- pangalan ng Lucide icon
  link         VARCHAR(255) NULL,              -- URL ng proyekto (opsyonal)
  is_featured  TINYINT(1) NOT NULL DEFAULT 0,  -- 1 = lalabas sa landing page
  sort_order   INT NOT NULL DEFAULT 0,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Talahanayan ng mga skill
CREATE TABLE IF NOT EXISTS skills (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(60) NOT NULL,
  level      TINYINT UNSIGNED NOT NULL,  -- 0 hanggang 100
  sort_order INT NOT NULL DEFAULT 0
);

-- Panimulang data ng mga proyekto
INSERT INTO projects (title, description, technologies, icon, link, is_featured, sort_order) VALUES
('Personal Portfolio', 'A responsive personal portfolio website built using PHP, HTML, CSS, and JavaScript.', 'PHP,HTML,CSS,JavaScript', 'globe', NULL, 1, 1),
('Student Management System', 'A web-based system concept for managing student information and records.', 'PHP,MySQL,HTML,CSS', 'users', NULL, 1, 2),
('Web Application', 'A responsive web application created as a learning project for practicing frontend and backend development.', 'PHP,JavaScript,CSS', 'monitor', NULL, 1, 3),
('Database Project', 'A database-driven project focused on storing, organizing, and retrieving information.', 'MySQL,PHP', 'database', NULL, 0, 4);

-- Panimulang data ng mga skill
INSERT INTO skills (name, level, sort_order) VALUES
('HTML', 90, 1),
('CSS', 85, 2),
('JavaScript', 70, 3),
('PHP', 80, 4),
('MySQL', 75, 5),
('Git / GitHub', 65, 6);

-- Admin account (ito lang ang may access sa dashboard)
-- Username: johnrave   |   Palitan agad ang password sa dashboard pagkatapos mag-login
INSERT INTO users (username, email, password_hash, role) VALUES
('johnrave', 'orpianojohnrave@gmail.com', '$2y$10$pqv6LAtytIhoUq0goi0U5umW.iBsP37xuhL6QmTHrgMsCfNzMuCTO', 'admin');
