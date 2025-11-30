<?php
// Start session (to know if we’re logged in)
use classes\Database;

session_start();

// Page metadata
$pageTitle = "About Tech‑Tok";
$pageDesc  = "Learn more about the creator and browse all posts";
require './includes/header.php';

// requires
require_once './classes/Database.php';
require_once './classes/ContentCrud.php';
require_once './classes/UserCrud.php';

// Get DB and CRUD objects
$db        = (new Database())->getConnection();
$postCrud  = new ContentCrud($db);
$userCrud  = new UserCrud($db);

// Fetch all posts
$stmt = $postCrud->readAll();
?>

    <!-- Owner Profile Section -->
    <section class="about-owner">
        <div class="owner-container">
            <img src="./images/myimage.jpg" alt="Tech‑Tok Creator" class="owner-avatar">
            <div class="owner-bio">
                <h2>Charles Chime</h2>
                <p>Hello! I’m Charles, the creator of Tech‑Tok. I love drawing vibrant illustrations, teaching web development to beginners, and exploring new tech. When I’m not behind the keyboard, you’ll find me sketching characters or brewing the perfect cup of coffee for my next coding session. Welcome to my platform—let’s create amazing stories together!</p>
            </div>
        </div>
    </section>

    <!-- All Posts Section -->
    <section class="section-title">
        <h2>All Posts</h2>
    </section>

    <section class="posts-list">
        <?php if ($stmt && $stmt->rowCount() > 0): ?>
            <?php while ($post = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <?php
                // get author name
                $userCrud->id = $post['user_id'];
                $author = $userCrud->readOne();
                $authorName = $author['name'] ?? 'Unknown';
                ?>
                <article class="post-card">
                    <?php if (!empty($post['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="project-img">
                    <?php endif; ?>
                    <div class="post-content">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <small>By <?= htmlspecialchars($authorName) ?> on <?= htmlspecialchars($post['created_at']) ?></small>
                        <p><?= nl2br(htmlspecialchars(substr($post['body'], 0, 200))) ?>…</p>
                        <div class="post-actions">
                            <a href="content.php" class="btn btn-primary">View All</a>
                            <?php if (!empty($_SESSION['user_id'])): ?>
                                <a href="update_content.php?id=<?= $post['id'] ?>" class="btn btn-warning">Edit</a>
                                <a href="./actions/post_delete.php?id=<?= $post['id'] ?>"
                                   onclick="return confirm('Delete this post?');"
                                   class="btn btn-danger">Delete</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>No posts yet!</p>
            </div>
        <?php endif; ?>
    </section>

<?php
require_once './includes/footer.php';
?>
