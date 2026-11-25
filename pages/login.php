<?php
session_start();
require_once __DIR__ . "/../includes/db.php";

// Redirect an already authenticated user directly to the dashboard.
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: ../dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    try {
        // Allow the user to sign in with either username or email.
        $stmt = db()->prepare(
            "SELECT id, username, password_hash, role FROM users WHERE username = :u OR email = :e LIMIT 1"
        );
        $stmt->execute(["u" => $login, "e" => $login]);
        $user = $stmt->fetch();

        // Compare the entered password with the stored password hash.
        if ($user && password_verify($password, $user["password_hash"])) {
            session_regenerate_id(true); // Generate a new session ID to reduce session fixation risk.
            $_SESSION["logged_in"] = true;
            $_SESSION["user_id"] = (int) $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            header("Location: ../dashboard.php");
            exit;
        }

        $error = "Invalid username or password.";
    } catch (PDOException $e) {
        error_log("Login DB error: " . $e->getMessage());
        $error = "Something went wrong. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
	<base href="../">
    <link rel="stylesheet" href="assets/css/style.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="login-page">

<h2>Admin Login</h2>
<div class="container" id="container">
	<!-- The sign-in form is the only authentication option currently available. -->
	<div class="form-container sign-in-container">
		<form action="pages/login.php" method="post">
			<h1>Sign in</h1>
			<span>Sign in with your account</span>
			<input type="text" name="username" placeholder="Username or Email" autocomplete="username" aria-label="Username or Email" required />
			<input type="password" name="password" placeholder="Password" autocomplete="current-password" aria-label="Password" required />
			<?php if ($error !== ""): ?>
				<p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p>
			<?php endif; ?>
			<button type="submit">Sign In</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-right">
				<h1>Welcome Back!</h1>
				<p>Sign in to manage your portfolio.</p>
			</div>
		</div>
	</div>
</div>

</body>
</html>
