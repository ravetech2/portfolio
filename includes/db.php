<?php
// Koneksyon sa MySQL gamit ang PDO.
// Palitan ang mga value sa ibaba ayon sa setup ng iyong server (XAMPP/Laragon: user "root", walang password).
const DB_HOST = "localhost";
const DB_NAME = "portfolio_db";
const DB_USER = "root";
const DB_PASS = "";

// Ibinabalik ang iisang koneksyon sa database (gagawa lang ng bago kung wala pa)
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // magtatapon ng error kapag pumalya ang query
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,                    // totoong prepared statements laban sa SQL injection
        ]);
    }

    return $pdo;
}

// Pinipigilan ang mga "javascript:" na link; http at https lang ang pinapayagan
function safe_url(?string $url): string
{
    return ($url !== null && preg_match('#^https?://#i', $url)) ? $url : "#";
}
