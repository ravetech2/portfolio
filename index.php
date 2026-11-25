<?php
$pageTitle = "John Rave Orpiano | Home";

require_once __DIR__ . "/includes/db.php";

// Kunin ang data para sa landing page mula sa database.
// Kung hindi gumana ang database, may default pa rin na laman ang page (hindi mag-crash).
$featuredProjects = [];
$skills = [];
$projectCount = 0;
$skillCount = 0;

try {
    $pdo = db();

    // Mga featured na proyekto (hanggang 3)
    $featuredProjects = $pdo->query(
        "SELECT title, description, technologies, icon, link
         FROM projects WHERE is_featured = 1
         ORDER BY sort_order LIMIT 3"
    )->fetchAll();

    // Mga pangunahing skill (hanggang 4, pinakamataas muna)
    $skills = $pdo->query(
        "SELECT name, level FROM skills ORDER BY level DESC LIMIT 4"
    )->fetchAll();

    // Bilang ng lahat ng proyekto at skill para sa stats
    $projectCount = (int) $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    $skillCount = (int) $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
} catch (PDOException $e) {
    error_log("Index DB error: " . $e->getMessage()); // itala sa log, huwag ipakita sa bisita
}

include "includes/header.php";
?>
<!-- Main content -->
<main>
    <!-- Hero: unang makikita ng bisita -->
    <section class="hero">
        <div class="container hero-container">
            <div class="hero-text">
                <p class="small-title">HELLO, I'M</p>
                <h1>John Rave<br><span>Rodriguez Orpiano</span></h1>
                <h2>Student &amp; Aspiring Web Developer</h2>
                <p class="hero-description">I am a passionate student interested in web development, programming, and
                    technology. I enjoy creating useful, responsive, and user-friendly websites.</p>
                <div class="hero-buttons">
                    <a href="pages/projects.php" class="btn primary-btn">View My Projects <i
                            data-lucide="arrow-right"></i></a>
                    <a href="pages/contact.php" class="btn outline-btn">Contact Me</a>
                </div>
                <div class="quick-info">
                    <div><i data-lucide="map-pin"></i><span>Pangasinan, Philippines</span></div>
                    <div><i data-lucide="graduation-cap"></i><span>College Student</span></div>
                </div>
            </div>
            <div class="hero-image-wrapper">
                <div class="hero-circle"><img src="assets/images/image.png"
                        alt="Profile placeholder for John Rave Rodriguez Orpiano"></div>
                <div class="floating-card card-one"><i data-lucide="code-2"></i>
                    <div><strong>Developer</strong><small>In Progress</small></div>
                </div>
                <div class="floating-card card-two"><i data-lucide="sparkles"></i>
                    <div><strong>Creative</strong><small>Problem Solver</small></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats: bilang galing sa database -->
    <section class="stats-section">
        <div class="container stats-grid">
            <div class="stat-card"><strong><?= $projectCount ?></strong><span>Projects</span></div>
            <div class="stat-card"><strong><?= $skillCount ?></strong><span>Skills</span></div>
            <div class="stat-card"><strong>Open</strong><span>To Opportunities</span></div>
        </div>
    </section>

    <!-- Ano ang ginagawa ko -->
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="section-label">WHAT I DO</p>
                <h2>Building Ideas Into <span>Web Experiences</span></h2>
                <p>I am continuously learning and improving my skills in programming and web development.</p>
            </div>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i data-lucide="layout"></i></div>
                    <h3>Web Development</h3>
                    <p>Creating responsive and functional websites using modern web technologies.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i data-lucide="code"></i></div>
                    <h3>Programming</h3>
                    <p>Developing logical solutions and applications through programming.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i data-lucide="lightbulb"></i></div>
                    <h3>Problem Solving</h3>
                    <p>Learning how to analyze problems and create practical technical solutions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured na mga proyekto (galing sa database) -->
    <?php if ($featuredProjects): ?>
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="section-label">FEATURED WORK</p>
                <h2>Recent <span>Projects</span></h2>
                <p>A few of the projects I have worked on and am still improving.</p>
            </div>
            <div class="projects-grid">
                <?php foreach ($featuredProjects as $project): ?>
                    <article class="project-card">
                        <div class="project-icon"><i data-lucide="<?= htmlspecialchars($project["icon"]) ?>"></i></div>
                        <div class="project-body">
                            <h2><?= htmlspecialchars($project["title"]) ?></h2>
                            <p><?= htmlspecialchars($project["description"]) ?></p>
                            <div class="tech-list">
                                <?php foreach (explode(",", $project["technologies"]) as $tech): ?>
                                    <span><?= htmlspecialchars(trim($tech)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="section-link">
                <a href="pages/projects.php" class="btn outline-btn">See All Projects <i data-lucide="arrow-right"></i></a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Mga pangunahing skill (galing sa database) -->
    <?php if ($skills): ?>
    <section class="section skills-section">
        <div class="container">
            <div class="section-heading">
                <p class="section-label">TOP SKILLS</p>
                <h2>Technologies I <span>Work With</span></h2>
            </div>
            <div class="skills-grid">
                <?php foreach ($skills as $skill): ?>
                    <div class="skill">
                        <div class="skill-title">
                            <span><?= htmlspecialchars($skill["name"]) ?></span>
                            <span><?= (int) $skill["level"] ?>%</span>
                        </div>
                        <div class="progress">
                            <div style="width:<?= (int) $skill["level"] ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="section-link">
                <a href="pages/about.php" class="btn outline-btn">More About Me <i data-lucide="arrow-right"></i></a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Call to action: mag-contact -->
    <section class="cta-section">
        <div class="container cta-content">
            <div>
                <p class="section-label">LET'S CONNECT</p>
                <h2>Have a project or idea?</h2>
                <p>Feel free to get in touch with me.</p>
            </div>
            <a href="pages/contact.php" class="btn primary-btn">Contact Me <i data-lucide="arrow-right"></i></a>
        </div>
    </section>
</main>
<?php include "includes/footer.php"; ?>
