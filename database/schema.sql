CREATE DATABASE IF NOT EXISTS portfolio_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portfolio_db;

CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(50)  NOT NULL UNIQUE,
  email         VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(120) NOT NULL,
  message    TEXT NOT NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projects (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title        VARCHAR(120) NOT NULL,
  description  TEXT NOT NULL,
  technologies VARCHAR(255) NOT NULL,
  icon         VARCHAR(50)  NOT NULL DEFAULT 'globe',
  link         VARCHAR(255) NULL,
  is_featured  TINYINT(1) NOT NULL DEFAULT 0,
  sort_order   INT NOT NULL DEFAULT 0,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS skills (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(60) NOT NULL,
  level      TINYINT UNSIGNED NOT NULL,
  sort_order INT NOT NULL DEFAULT 0
);

INSERT INTO projects (title, description, technologies, icon, link, is_featured, sort_order) VALUES
('Personal Portfolio', 'A responsive personal portfolio website built using PHP, HTML, CSS, and JavaScript.', 'PHP,HTML,CSS,JavaScript', 'globe', NULL, 1, 1),
('Student Management System', 'A web-based system concept for managing student information and records.', 'PHP,MySQL,HTML,CSS', 'users', NULL, 1, 2),
('Web Application', 'A responsive web application created as a learning project for practicing frontend and backend development.', 'PHP,JavaScript,CSS', 'monitor', NULL, 1, 3),
('Database Project', 'A database-driven project focused on storing, organizing, and retrieving information.', 'MySQL,PHP', 'database', NULL, 0, 4);

INSERT INTO skills (name, level, sort_order) VALUES
('HTML', 90, 1),
('CSS', 85, 2),
('JavaScript', 70, 3),
('PHP', 80, 4),
('MySQL', 75, 5),
('Git / GitHub', 65, 6);

INSERT INTO users (username, email, password_hash, role) VALUES
('johnrave', 'orpianojohnrave@gmail.com', '$2y$10$pqv6LAtytIhoUq0goi0U5umW.iBsP37xuhL6QmTHrgMsCfNzMuCTO', 'admin');
