<?php
// Start session and protect page
use classes\Database;

session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Page metadata for header
$pageTitle = "Post Management";
$pageDesc  = "View, edit, or delete blog posts";
//requires
require_once './includes/header.php';
// Bring in Database, Content and User CRUD classes
require_once './classes/Database.php';
require_once './classes/ContentCrud.php';
require_once './classes/UserCrud.php';

// Get PDO connection and instances
$db       = (new Database())->getConnection();
$postCrud = new ContentCrud($db);
$userCrud = new UserCrud($db);

// Fetch all posts
$stmt = $postCrud->readAll();
?>

<section class="lesson-masthead">
    <h1>All Posts</h1>
</section>

<section class="add-user-row">
    <a href="create_content.php" class="btn btn-primary">Add New Post</a>
</section>

<section class="user-list-row">
    <table class="table table-striped align-middle">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Author</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php while ($post = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <?php
            // Load author name separately
            $userCrud->id   = $post['user_id'];
            $authorData     = $userCrud->readOne();
            $authorName     = $authorData['name'] ?? 'Unknown';
            ?>
            <tr>
                <td><?= htmlspecialchars($post['id']) ?></td>
                <td>
                    <?php if (!empty($post['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($post['image']) ?>"
                             alt="Post image" class="author-avatar" />
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($post['title']) ?></td>
                <td><?= htmlspecialchars($authorName) ?></td>
                <td><?= htmlspecialchars($post['created_at']) ?></td>
                <td>
                    <a href="update_content.php?id=<?= $post['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="actions/post_delete.php?id=<?= $post['id'] ?>"
                       onclick="return confirm('Delete this post?');"
                       class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<?php
require_once './includes/footer.php';
?>
