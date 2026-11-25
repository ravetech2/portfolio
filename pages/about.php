<?php
$pageTitle = "About Me | John Rave Orpiano";
include __DIR__ . "/../includes/header.php";
?>
<main>
    <section class="page-header">
        <div class="container">
            <p class="section-label">ABOUT ME</p>
            <h1>Get to Know <span>Me</span></h1>
            <p>A student passionate about technology, programming, and web development.</p>
        </div>
    </section>

    <!-- About me -->
    <section class="section">
        <div class="container about-grid">
            <div class="about-image"><img src="assets/images/image.png" alt="Profile placeholder"></div>
            <div class="about-content">
                <p class="section-label">WHO I AM</p>
                <h2>I'm John Rave Rodriguez Orpiano.</h2>
                <p>I am a college student from Pangasinan, Philippines, with an interest in information technology,
                    programming, and web development.</p>
                <p>I enjoy learning new technologies and developing projects that allow me to apply what I have learned.
                    My goal is to continuously improve my technical, creative, and problem-solving skills.</p>
                <p>I believe that every project is an opportunity to learn something new and become a better developer.
                </p>
                <div class="about-details">
                    <div><strong>Name</strong><span>John Rave Rodriguez Orpiano</span></div>
                    <div><strong>Location</strong><span>Pangasinan, Philippines</span></div>
                    <div><strong>Field</strong><span>Information Technology</span></div>
                    <div><strong>Role</strong><span>Student / Aspiring Developer</span></div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Skills -->
    <section class="section skills-section">
        <div class="container">
            <div class="section-heading">
                <p class="section-label">MY SKILLS</p>
                <h2>Technologies I <span>Work With</span></h2>
            </div>
            <div class="skills-grid">
                <div class="skill">
                    <div class="skill-title"><span>HTML</span><span>90%</span></div>
                    <div class="progress">
                        <div style="width:90%"></div>
                    </div>
                </div>
                <div class="skill">
                    <div class="skill-title"><span>CSS</span><span>85%</span></div>
                    <div class="progress">
                        <div style="width:85%"></div>
                    </div>
                </div>
                <div class="skill">
                    <div class="skill-title"><span>JavaScript</span><span>70%</span></div>
                    <div class="progress">
                        <div style="width:70%"></div>
                    </div>
                </div>
                <div class="skill">
                    <div class="skill-title"><span>PHP</span><span>80%</span></div>
                    <div class="progress">
                        <div style="width:80%"></div>
                    </div>
                </div>
                <div class="skill">
                    <div class="skill-title"><span>MySQL</span><span>75%</span></div>
                    <div class="progress">
                        <div style="width:75%"></div>
                    </div>
                </div>
                <div class="skill">
                    <div class="skill-title"><span>Git / GitHub</span><span>65%</span></div>
                    <div class="progress">
                        <div style="width:65%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="section-label">EDUCATION</p>
                <h2>My <span>Education</span></h2>
            </div>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content"><span class="timeline-date">Present</span>
                        <h3>College Education</h3>
                        <h4>Pangasinan State University</h4>
                        <p>Currently developing knowledge and skills in information technology, programming, web
                            development, and related fields.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include __DIR__ . "/../includes/footer.php"; ?>