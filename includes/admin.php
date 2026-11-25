<?php
// Administrator helpers for authentication, authorization, and CSRF protection.
require_once __DIR__ . "/auth.php"; // Start the session and verify that the user is logged in.
require_once __DIR__ . "/db.php";

// Create or retrieve the CSRF token used by dashboard forms.
function csrf_token(): string
{
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf"];
}

// Verify that the POST request contains a valid token from this application.
function csrf_check(): void
{
    if (!hash_equals($_SESSION["csrf"] ?? "", $_POST["csrf"] ?? "")) {
        http_response_code(400);
        exit("Invalid request.");
    }
}

// Check the user's role in the database rather than relying only on session data.
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

// Shortcut for safely escaping HTML output.
function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}
