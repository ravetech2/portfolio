<?php
// Mga tulong para sa admin dashboard: login check, role check, at CSRF protection
require_once __DIR__ . "/auth.php"; // session_start() at pag-check kung naka-login
require_once __DIR__ . "/db.php";

// Gumagawa/kumukuha ng CSRF token para sa mga form
function csrf_token(): string
{
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf"];
}

// Tinitiyak na galing sa sarili nating form ang POST request
function csrf_check(): void
{
    if (!hash_equals($_SESSION["csrf"] ?? "", $_POST["csrf"] ?? "")) {
        http_response_code(400);
        exit("Invalid request.");
    }
}

// Sinusuri sa database kung admin ang naka-login na user (hindi lang sa session)
function is_admin(): bool
{
    if (empty($_SESSION["user_id"])) {
        return false;
    }
    try {
        $stmt = db()->prepare("SELECT role FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(["id" => $_SESSION["user_id"]]);
        return $stmt->fetchColumn() === "admin";
    } catch (PDOException $e) {
        error_log("is_admin error: " . $e->getMessage());
        return false;
    }
}

// Shortcut para sa htmlspecialchars
function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}
