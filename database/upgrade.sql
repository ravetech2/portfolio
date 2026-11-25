-- Para lang ito sa mga nakapag-import na ng lumang schema.sql.
-- Kung bago pa lang ang database mo, hindi mo na ito kailangan.
USE portfolio_db;

-- Idagdag ang "role" column sa users table
ALTER TABLE users
  ADD COLUMN role ENUM('admin','user') NOT NULL DEFAULT 'user' AFTER password_hash;

-- Gumawa ng admin account (o i-reset kung nandiyan na ang username)
-- Username: johnrave   |   Palitan agad ang password sa dashboard pagkatapos mag-login
INSERT INTO users (username, email, password_hash, role) VALUES
('johnrave', 'orpianojohnrave@gmail.com', '$2y$10$pqv6LAtytIhoUq0goi0U5umW.iBsP37xuhL6QmTHrgMsCfNzMuCTO', 'admin')
ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), role = 'admin';
