<?php
// Admin dashboard: para lang sa mga user na may role na "admin"
require_once "includes/admin.php";

// Ang title ay ang username ng naka-login, hindi ang salitang "admin"
$pageTitle = $_SESSION["username"];

// Kung hindi admin, ipakita ang 403 at huwag ituloy
if (!is_admin()) {
    http_response_code(403);
    include "includes/header.php";
    ?>
    <main class="dashboard-page">
        <section class="container dashboard-panel">
            <h1><?= h($_SESSION["username"]) ?></h1>
            <p>You don't have permission to view this page.</p>
            <a class="btn primary-btn dashboard-logout" href="pages/logout.php">Logout</a>
        </section>
    </main>
    <?php
    include "includes/footer.php";
    exit;
}

// Mensahe ("flash") na ipapakita pagkatapos ng isang aksyon
function flash(string $type, string $text): void
{
    $_SESSION["flash"] = ["type" => $type, "text" => $text];
}

// ---------- PAGPROSESO NG MGA AKSYON (POST) ----------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_check();
    $action = $_POST["action"] ?? "";
    $anchor = "";

    try {
        $pdo = db();

        switch ($action) {
            // Magdagdag ng proyekto
            case "project_add":
                $anchor = "#projects";
                $title = trim($_POST["title"] ?? "");
                $description = trim($_POST["description"] ?? "");
                $technologies = trim($_POST["technologies"] ?? "");
                $icon = strtolower(trim($_POST["icon"] ?? "globe"));
                $link = trim($_POST["link"] ?? "");
                $featured = isset($_POST["is_featured"]) ? 1 : 0;

                if ($title === "" || $description === "" || $technologies === "") {
                    flash("error", "Title, description, and technologies are required.");
                } elseif (mb_strlen($title) > 120 || mb_strlen($technologies) > 255) {
                    flash("error", "Title or technologies is too long.");
                } elseif (!preg_match('/^[a-z0-9-]{1,50}$/', $icon)) {
                    flash("error", "Icon must be a Lucide icon name, e.g. globe or database.");
                } elseif ($link !== "" && !preg_match('#^https?://#i', $link)) {
                    flash("error", "Link must start with http:// or https://");
                } else {
                    $next = (int) $pdo->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM projects")->fetchColumn();
                    $stmt = $pdo->prepare(
                        "INSERT INTO projects (title, description, technologies, icon, link, is_featured, sort_order)
                         VALUES (:t, :d, :tech, :i, :l, :f, :s)"
                    );
                    $stmt->execute([
                        "t" => $title, "d" => $description, "tech" => $technologies,
                        "i" => $icon, "l" => $link === "" ? null : $link, "f" => $featured, "s" => $next,
                    ]);
                    flash("success", "Project added.");
                }
                break;

            // I-on/off ang "featured" ng proyekto (lalabas sa landing page)
            case "project_toggle":
                $anchor = "#projects";
                $pdo->prepare("UPDATE projects SET is_featured = 1 - is_featured WHERE id = :id")
                    ->execute(["id" => (int) ($_POST["id"] ?? 0)]);
                flash("success", "Project updated.");
                break;

            // Magtanggal ng proyekto
            case "project_delete":
                $anchor = "#projects";
                $pdo->prepare("DELETE FROM projects WHERE id = :id")
                    ->execute(["id" => (int) ($_POST["id"] ?? 0)]);
                flash("success", "Project deleted.");
                break;

            // Magdagdag ng skill
            case "skill_add":
                $anchor = "#skills";
                $name = trim($_POST["name"] ?? "");
                $level = filter_var($_POST["level"] ?? "", FILTER_VALIDATE_INT);

                if ($name === "" || mb_strlen($name) > 60) {
                    flash("error", "Skill name is required (max 60 characters).");
                } elseif ($level === false || $level < 0 || $level > 100) {
                    flash("error", "Level must be a number from 0 to 100.");
                } else {
                    $next = (int) $pdo->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM skills")->fetchColumn();
                    $pdo->prepare("INSERT INTO skills (name, level, sort_order) VALUES (:n, :l, :s)")
                        ->execute(["n" => $name, "l" => $level, "s" => $next]);
                    flash("success", "Skill added.");
                }
                break;

            // Magtanggal ng skill
            case "skill_delete":
                $anchor = "#skills";
                $pdo->prepare("DELETE FROM skills WHERE id = :id")
                    ->execute(["id" => (int) ($_POST["id"] ?? 0)]);
                flash("success", "Skill deleted.");
                break;

            // Markahan bilang nabasa na ang mensahe
            case "message_read":
                $anchor = "#messages";
                $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = :id")
                    ->execute(["id" => (int) ($_POST["id"] ?? 0)]);
                flash("success", "Message marked as read.");
                break;

            // Magtanggal ng mensahe
            case "message_delete":
                $anchor = "#messages";
                $pdo->prepare("DELETE FROM messages WHERE id = :id")
                    ->execute(["id" => (int) ($_POST["id"] ?? 0)]);
                flash("success", "Message deleted.");
                break;

            // Palitan ang password ng admin
            case "password_change":
                $anchor = "#account";
                $current = $_POST["current_password"] ?? "";
                $new = $_POST["new_password"] ?? "";
                $confirm = $_POST["confirm_password"] ?? "";

                $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = :id LIMIT 1");
                $stmt->execute(["id" => $_SESSION["user_id"]]);
                $currentHash = $stmt->fetchColumn();

                if (!$currentHash || !password_verify($current, $currentHash)) {
                    flash("error", "Current password is incorrect.");
                } elseif (strlen($new) < 8) {
                    flash("error", "New password must be at least 8 characters.");
                } elseif ($new !== $confirm) {
                    flash("error", "New passwords do not match.");
                } else {
                    // I-hash ang bagong password bago i-save
                    $pdo->prepare("UPDATE users SET password_hash = :p WHERE id = :id")
                        ->execute(["p" => password_hash($new, PASSWORD_DEFAULT), "id" => $_SESSION["user_id"]]);
                    session_regenerate_id(true);
                    flash("success", "Password changed.");
                }
                break;

            default:
                flash("error", "Unknown action.");
        }
    } catch (PDOException $e) {
        error_log("Dashboard action error: " . $e->getMessage());
        flash("error", "Something went wrong. Please try again.");
    }

    // Post/Redirect/Get: iwas dobleng submit kapag nag-refresh
    header("Location: dashboard.php" . $anchor);
    exit;
}

// ---------- PAGKUHA NG DATA PARA IPAKITA ----------
$stats = ["projects" => 0, "skills" => 0, "messages" => 0, "unread" => 0];
$messages = [];
$projects = [];
$skills = [];

try {
    $pdo = db();
    $stats["projects"] = (int) $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    $stats["skills"]   = (int) $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
    $stats["messages"] = (int) $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
    $stats["unread"]   = (int) $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();

    $messages = $pdo->query("SELECT id, name, email, message, is_read, created_at FROM messages ORDER BY created_at DESC, id DESC LIMIT 50")->fetchAll();
    $projects = $pdo->query("SELECT id, title, technologies, is_featured FROM projects ORDER BY sort_order, id")->fetchAll();
    $skills   = $pdo->query("SELECT id, name, level FROM skills ORDER BY sort_order, id")->fetchAll();
} catch (PDOException $e) {
    error_log("Dashboard load error: " . $e->getMessage());
}

// Kunin at burahin ang flash message (isang beses lang ipapakita)
$flash = $_SESSION["flash"] ?? null;
unset($_SESSION["flash"]);

$csrf = csrf_token();

include "includes/header.php";
?>

<main class="dashboard-page">
    <section class="container dashboard-panel">

        <p class="section-label">ADMIN DASHBOARD</p>
        <!-- Username ng naka-login ang title -->
        <h1><?= h($_SESSION["username"]) ?></h1>

        <div class="dash-actions">
            <a class="btn outline-btn" href="index.php">View Site</a>
            <a class="btn primary-btn" href="pages/logout.php">Logout</a>
        </div>

        <?php if ($flash): ?>
            <div class="alert <?= $flash["type"] === "success" ? "success" : "error" ?>"><?= h($flash["text"]) ?></div>
        <?php endif; ?>

        <!-- Stat cards -->
        <div class="stats-grid four">
            <div class="stat-card"><strong><?= $stats["projects"] ?></strong><span>Projects</span></div>
            <div class="stat-card"><strong><?= $stats["skills"] ?></strong><span>Skills</span></div>
            <div class="stat-card"><strong><?= $stats["messages"] ?></strong><span>Messages</span></div>
            <div class="stat-card"><strong><?= $stats["unread"] ?></strong><span>Unread</span></div>
        </div>

        <!-- ===== MESSAGES ===== -->
        <div class="dash-card" id="messages">
            <h3>Messages</h3>
            <?php if (!$messages): ?>
                <p>No messages yet.</p>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="dash-table">
                        <thead>
                            <tr><th>From</th><th>Message</th><th>Date</th><th>Status</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $m): ?>
                                <tr>
                                    <td><strong><?= h($m["name"]) ?></strong><br><small><?= h($m["email"]) ?></small></td>
                                    <td class="msg-cell"><?= nl2br(h($m["message"])) ?></td>
                                    <td><?= h(date("M d, Y", strtotime($m["created_at"]))) ?></td>
                                    <td><span class="badge <?= $m["is_read"] ? "" : "badge-new" ?>"><?= $m["is_read"] ? "Read" : "New" ?></span></td>
                                    <td class="row-actions">
                                        <?php if (!$m["is_read"]): ?>
                                            <form method="post">
                                                <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                                                <input type="hidden" name="action" value="message_read">
                                                <input type="hidden" name="id" value="<?= (int) $m["id"] ?>">
                                                <button class="btn-sm" type="submit">Mark read</button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="post" onsubmit="return confirm('Delete this message?')">
                                            <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                                            <input type="hidden" name="action" value="message_delete">
                                            <input type="hidden" name="id" value="<?= (int) $m["id"] ?>">
                                            <button class="btn-sm btn-danger" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== PROJECTS ===== -->
        <div class="dash-card" id="projects">
            <h3>Projects</h3>
            <?php if ($projects): ?>
                <div class="table-wrap">
                    <table class="dash-table">
                        <thead>
                            <tr><th>Title</th><th>Technologies</th><th>Featured</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $p): ?>
                                <tr>
                                    <td><?= h($p["title"]) ?></td>
                                    <td><?= h($p["technologies"]) ?></td>
                                    <td><span class="badge <?= $p["is_featured"] ? "badge-new" : "" ?>"><?= $p["is_featured"] ? "Yes" : "No" ?></span></td>
                                    <td class="row-actions">
                                        <form method="post">
                                            <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                                            <input type="hidden" name="action" value="project_toggle">
                                            <input type="hidden" name="id" value="<?= (int) $p["id"] ?>">
                                            <button class="btn-sm" type="submit">Toggle featured</button>
                                        </form>
                                        <form method="post" onsubmit="return confirm('Delete this project?')">
                                            <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                                            <input type="hidden" name="action" value="project_delete">
                                            <input type="hidden" name="id" value="<?= (int) $p["id"] ?>">
                                            <button class="btn-sm btn-danger" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No projects yet.</p>
            <?php endif; ?>

            <h4 class="dash-subtitle">Add Project</h4>
            <form method="post" class="dash-form">
                <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                <input type="hidden" name="action" value="project_add">
                <div class="form-group"><label for="p-title">Title</label><input type="text" id="p-title" name="title" maxlength="120" required></div>
                <div class="form-group"><label for="p-tech">Technologies (comma-separated)</label><input type="text" id="p-tech" name="technologies" placeholder="PHP,MySQL" maxlength="255" required></div>
                <div class="form-group"><label for="p-icon">Lucide icon</label><input type="text" id="p-icon" name="icon" value="globe" maxlength="50"></div>
                <div class="form-group"><label for="p-link">Link (optional)</label><input type="url" id="p-link" name="link" placeholder="https://"></div>
                <div class="form-group full"><label for="p-desc">Description</label><textarea id="p-desc" name="description" rows="3" required></textarea></div>
                <label class="check"><input type="checkbox" name="is_featured" value="1"> Show on landing page</label>
                <button type="submit" class="btn primary-btn">Add Project</button>
            </form>
        </div>

        <!-- ===== SKILLS ===== -->
        <div class="dash-card" id="skills">
            <h3>Skills</h3>
            <?php if ($skills): ?>
                <div class="table-wrap">
                    <table class="dash-table">
                        <thead>
                            <tr><th>Skill</th><th>Level</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($skills as $s): ?>
                                <tr>
                                    <td><?= h($s["name"]) ?></td>
                                    <td><?= (int) $s["level"] ?>%</td>
                                    <td class="row-actions">
                                        <form method="post" onsubmit="return confirm('Delete this skill?')">
                                            <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                                            <input type="hidden" name="action" value="skill_delete">
                                            <input type="hidden" name="id" value="<?= (int) $s["id"] ?>">
                                            <button class="btn-sm btn-danger" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No skills yet.</p>
            <?php endif; ?>

            <h4 class="dash-subtitle">Add Skill</h4>
            <form method="post" class="dash-form">
                <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                <input type="hidden" name="action" value="skill_add">
                <div class="form-group"><label for="s-name">Skill name</label><input type="text" id="s-name" name="name" maxlength="60" required></div>
                <div class="form-group"><label for="s-level">Level (0-100)</label><input type="number" id="s-level" name="level" min="0" max="100" required></div>
                <button type="submit" class="btn primary-btn">Add Skill</button>
            </form>
        </div>

        <!-- ===== ACCOUNT: palitan ang password ===== -->
        <div class="dash-card" id="account">
            <h3>Change Password</h3>
            <form method="post" class="dash-form">
                <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                <input type="hidden" name="action" value="password_change">
                <div class="form-group full"><label for="pw-current">Current password</label><input type="password" id="pw-current" name="current_password" autocomplete="current-password" required></div>
                <div class="form-group"><label for="pw-new">New password (min. 8 characters)</label><input type="password" id="pw-new" name="new_password" minlength="8" autocomplete="new-password" required></div>
                <div class="form-group"><label for="pw-confirm">Confirm new password</label><input type="password" id="pw-confirm" name="confirm_password" minlength="8" autocomplete="new-password" required></div>
                <button type="submit" class="btn primary-btn">Update Password</button>
            </form>
        </div>

    </section>
</main>

<?php include "includes/footer.php"; ?>
