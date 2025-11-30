<?php

use classes\Database;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
//page metadata
$pageTitle       = "Create New Post";
$pageDescription = "Publish a new story";

//requires
require_once './includes/header.php';
require_once './classes/Database.php';
require_once './classes/ContentCrud.php';
require_once './classes/Validate.php';

$db      = (new Database())->getConnection();
$post    = new ContentCrud($db);

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $body  = $_POST['body'] ?? '';
    $image = '';

    // 1) Validate text fields
    if (trim($title) === '') {
        $error = "Title cannot be empty.";
    } elseif (trim($body) === '') {
        $error = "Body cannot be empty.";
    }

    // 2) Image upload logic (only if no error)
    if (empty($error) && !empty($_FILES['image']['name'])) {
        // Generate a unique filename
        $image      = time() . '_' . basename($_FILES['image']['name']);
        $target_dir  = 'uploads/';
        $target_file = $target_dir . $image;

        // Check if the file is actually an image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $error = "File is not a valid image.";
        }
        // Attempt to move the uploaded file into place
        elseif (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $error = "Sorry, there was an error uploading your image.";
        }
    }

    // 3) Create post if no errors
    if (empty($error)) {
        $post->userId = $_SESSION['user_id'];
        $post->title  = $title;
        $post->body   = $body;
        $post->image  = $image;

        if ($post->create()) {
            $_SESSION['flash_success'] = "Post published successfully!";
            header('Location: content.php');
            exit;
        } else {
            $error = "Database error: could not publish post.";
        }
    }
}
?>


<section class="lesson-masthead">
    <h1>Create New Post</h1>
</section>

<section class="add-form-row">
    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label class="form-label">Title:</label><br>
        <input class="form-control" type="text" name="title"
               value="<?= htmlspecialchars($title ?? '') ?>" required><br><br>

        <label class="form-label">Body:</label><br>
        <textarea class="form-control" name="body" rows="6" required><?= htmlspecialchars($body ?? '') ?></textarea><br><br>

        <label class="form-label">Image (optional):</label><br>
        <input class="form-control" type="file" name="image" accept="image/*"><br><br>

        <button class="btn btn-primary" type="submit">Publish</button>
        <a class="btn btn-danger" href="content.php">Cancel</a>
    </form>
</section>

<?php require_once './includes/footer.php'; ?>
