<?php
// MySQL database connection settings.
// Change these values for another server or database setup.
const DB_HOST = "localhost";
const DB_NAME = "portfolio_db";
const DB_USER = "root";
const DB_PASS = "";

// Return one reusable database connection, creating it only when needed.
function db(): mysqli
{
    static $connection = null;

    if ($connection === null) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $connection->set_charset("utf8mb4");
    }

    return $connection;
}

// Allow only HTTP and HTTPS URLs and reject unsafe values such as javascript: links.
function safe_url(?string $url): string
{
    return ($url !== null && preg_match('#^https?://#i', $url)) ? $url : "#";
}
