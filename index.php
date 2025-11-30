<?php

use classes\Database;

session_start();

// Page metadata
$pageTitle = "Home";
$pageDesc  = "Welcome to Tech‑Tok: share your stories and discover new insights";

//requires
require_once './includes/header.php';
// Imports for fetching posts
require_once './classes/Database.php';
require_once './classes/ContentCrud.php';
require_once './classes/UserCrud.php';

$db       = (new Database())->getConnection();
$postCrud = new ContentCrud($db);
$userCrud = new UserCrud($db);

// Fetch latest 3 posts only
$stmt = $postCrud->readAll();
$posts = [];
if ($stmt) {
    for ($i = 0; $i < 3 && $row = $stmt->fetch(PDO::FETCH_ASSOC); $i++) {
        $posts[] = $row;
    }
}
?>

<!-- Home section after the header -->
<section id="home" class="hero-content">
    <h1>Welcome to CharlesGPT Tech‑Tok</h1>
    <p>Share Your Stories, Inspire the World</p>
    <div class="hero-buttons">
        <a href="#register" class="cta-button">Get Started Free</a>
        <a href="#about" class="cta-button">Learn More</a>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about">
    <div class="section-title">
        <h2>About Tech‑Tok</h2>
    </div>
    <div class="about-content">
        <p>Tech‑Tok is a modern content management platform designed for creators, bloggers, and businesses who want to share their ideas with the world. Publish articles, tutorials, and stories in minutes, engage with your audience, and grow your personal brand—all in one place.</p>
    </div>
</section>

<!-- Latest Stories Section -->
<section id="latest" class="portfolio">
    <div class="section-title">
        <h2>Latest Stories</h2>
    </div>
    <div class="portfolio-grid">
        <?php if (count($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <?php
                // get author name
                $userCrud->id   = $post['user_id'];
                $authorData     = $userCrud->readOne();
                $authorName     = $authorData['name'] ?? 'Unknown';
                ?>
                <div class="project">
                    <?php if (!empty($post['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($post['image']) ?>"
                             alt="<?= htmlspecialchars($post['title']) ?>"
                             class="project-img">
                    <?php endif; ?>

                    <div class="project-content">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <span class="project-meta"><?= htmlspecialchars($authorName) ?> • <?= htmlspecialchars($post['created_at']) ?></span>
                        <p><?= nl2br(htmlspecialchars(substr($post['body'], 0, 120))) ?>…</p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>No posts yet!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Register Section -->
<section id="register" class="register">
    <div class="register-content">
        <h2>Ready to Share Your Voice?</h2>
        <p>Create your free account today and start publishing your first story in minutes.</p>
        <a href="register.php" class="register-button">Register Now</a>
    </div>
</section>

<?php
require_once './includes/footer.php';
?>
