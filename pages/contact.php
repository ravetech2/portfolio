<?php
// Process contact-form submissions and save messages in the database.
require_once __DIR__ . "/../includes/db.php";

$pageTitle = "Contact Me | John Rave Orpiano";
$name = "";
$email = "";
$message = "";
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // Validate all input before saving it.
    if ($name === "" || $email === "" || $message === "") {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (mb_strlen($name) > 100 || mb_strlen($email) > 120 || mb_strlen($message) > 5000) {
        $error = "One of your entries is too long.";
    } else {
        try {
            // Save the message using a prepared statement to prevent SQL injection.
            $stmt = db()->prepare(
                "INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)"
            );
            $stmt->execute(["name" => $name, "email" => $email, "message" => $message]);

            $success = "Thank you, $name! Your message has been received.";
            // Clear the form after a successful submission.
            $name = "";
            $email = "";
            $message = "";
        } catch (PDOException $e) {
            error_log("Contact DB error: " . $e->getMessage());
            $error = "Sorry, your message could not be sent. Please try again later.";
        }
    }
}
include __DIR__ . "/../includes/header.php";
?>
<main>
    <section class="page-header">
        <div class="container">
            <p class="section-label">CONTACT</p>
            <h1>Let's <span>Connect</span></h1>
            <p>Have a question, project idea, or opportunity? Send me a message.</p>
        </div>
    </section>
    <section class="section">
        <div class="container contact-grid">
            <div class="contact-info">
                <p class="section-label">GET IN TOUCH</p>
                <h2>Let's work together.</h2>
                <p>I'm always interested in learning, collaborating, and working on interesting projects.</p>
                <div class="contact-item">
                    <div class="contact-icon"><i data-lucide="mail"></i></div>
                    <div><strong>Email</strong><span>orpianojohnrave@gmail.com</span></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i data-lucide="map-pin"></i></div>
                    <div><strong>Location</strong><span>Pangasinan, Philippines</span></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i data-lucide="clock"></i></div>
                    <div><strong>Availability</strong><span>Open to opportunities</span></div>
                </div>
            </div>
            <div class="contact-form-container">
                <?php if ($success): ?>
                    <div class="alert success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                <form method="POST" action="contact.php">
                    <div class="form-group"><label for="name">Your Name</label><input type="text" id="name" name="name"
                            value="<?= htmlspecialchars($name) ?>" placeholder="Enter your name" required></div>
                    <div class="form-group"><label for="email">Email Address</label><input type="email" id="email"
                            name="email" value="<?= htmlspecialchars($email) ?>" placeholder="Enter your email"
                            required></div>
                    <div class="form-group"><label for="message">Message</label><textarea id="message" name="message"
                            rows="7" placeholder="Write your message..."
                            required><?= htmlspecialchars($message) ?></textarea></div>
                    <button type="submit" class="btn primary-btn">Send Message <i data-lucide="send"></i></button>
                </form>
            </div>
        </div>
    </section>
</main>
<?php include __DIR__ . "/../includes/footer.php"; ?>
