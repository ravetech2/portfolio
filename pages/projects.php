<?php
$pageTitle = "Projects | John Rave Orpiano";

require_once __DIR__ . "/../includes/db.php";

// Retrieve all projects from the database.
$projects = [];
try {
    $projects = db()->query(
        "SELECT title, description, technologies, icon, link
         FROM projects ORDER BY sort_order, id"
    )->fetchAll();
} catch (PDOException $e) {
    error_log("Projects DB error: " . $e->getMessage());
}

include __DIR__ . "/../includes/header.php";
?>
<main>
    <section class="page-header">
        <div class="container">
            <p class="section-label">PORTFOLIO</p>
            <h1>My <span>Projects</span></h1>
            <p>Here are some projects I have worked on and projects I am currently developing.</p>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <?php if (!$projects): ?>
                <p>No projects to show yet.</p>
            <?php endif; ?>
            <div class="projects-grid">
                <?php foreach ($projects as $project): ?>
                    <?php $url = safe_url($project["link"]); ?>
                    <article class="project-card">
                        <div class="project-icon"><i data-lucide="<?= htmlspecialchars($project["icon"]) ?>"></i></div>
                        <div class="project-body">
                            <h2><?= htmlspecialchars($project["title"]) ?></h2>
                            <p><?= htmlspecialchars($project["description"]) ?></p>
                            <div class="tech-list">
                                <?php foreach (explode(",", $project["technologies"]) as $tech): ?><span><?= htmlspecialchars(trim($tech)) ?></span><?php endforeach; ?>
                            </div>
                            <a href="<?= htmlspecialchars($url) ?>" class="project-link"<?= $url !== "#" ? ' target="_blank" rel="noopener noreferrer"' : "" ?>>View Project <i data-lucide="arrow-up-right"></i></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
<?php include __DIR__ . "/../includes/footer.php"; ?>
