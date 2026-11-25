<?php
// MySQL database connection settings using PDO.
// Change these values for another server or database setup.
const DB_HOST = "localhost";
const DB_NAME = "portfolio_db";
const DB_USER = "root";
const DB_PASS = "";

// Return one reusable database connection, creating it only when needed.
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Throw an exception when a query fails.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,                    // Use native prepared statements for SQL injection protection.
        ]);
    }

    return $pdo;
}

// Allow only HTTP and HTTPS URLs and reject unsafe values such as javascript: links.
function safe_url(?string $url): string
{
    return ($url !== null && preg_match('#^https?://#i', $url)) ? $url : "#";
}
