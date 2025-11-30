<?php

use classes\Database;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

//page metadata
$pageTitle       = "Edit Post";
$pageDescription = "Update an existing story";

//requires
require_once './includes/header.php';
require_once './classes/Database.php';
require_once './classes/ContentCrud.php';
require_once './classes/Validate.php';

$db      = (new Database())->getConnection();
$post    = new ContentCrud($db);

$error   = '';
$success = '';

// Ensure ID
if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: content.php');
    exit;
}
$post->id = (int) $_GET['id'];
$data      = $post->readOne();
if (!$data) {
    header('Location: content.php');
    exit;
}

// Pre-fill
$title = $data['title'];
$body  = $data['body'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $body  = $_POST['body']  ?? '';
    $image = '';

    // Validate text
    if (trim($title) === '') {
        $error = "Title cannot be empty.";
    } elseif (trim($body) === '') {
        $error = "Body cannot be empty.";
    }

    // Image upload
    if (empty($error) && !empty($_FILES['image']['name'])) {
        $image       = time() . '_' . basename($_FILES['image']['name']);
        $target_dir  = "uploads/";
        $target_file = $target_dir . $image;

        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $error = "File is not a valid image.";
        } elseif (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $error = "Sorry, there was an error uploading your image.";
        }
    }

    // Update if there's no error
    if (empty($error)) {
        $post->title = $title;
        $post->body  = $body;
        if ($image !== '') {
            $post->image = $image;
        }

        if ($post->update()) {
            $_SESSION['flash_success'] = "Post updated successfully!";
            header('Location: content.php');
            exit;
        } else {
            $error = "Database error: could not update post.";
        }
    }
}
?>

<section class="lesson-masthead">
    <h1>Edit Post</h1>
</section>

<section class="add-form-row">
    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label class="form-label">Title:</label><br>
        <input class="form-control" type="text" name="title"
               value="<?= htmlspecialchars($title) ?>" required><br><br>

        <label class="form-label">Body:</label><br>
        <textarea class="form-control" name="body" rows="6" required><?= htmlspecialchars($body) ?></textarea><br><br>

        <label class="form-label">Image (optional):</label><br>
        <input class="form-control" type="file" name="image" accept="image/*"><br><br>

        <?php if (!empty($data['image'])): ?>
            <p>Current Image:</p>
            <img src="uploads/<?= htmlspecialchars($data['image']) ?>"
                 alt="Post image" class="project-img"><br><br>
        <?php endif; ?>

        <button class="btn btn-primary" type="submit">Update</button>
        <a class="btn btn-danger" href="content.php">Cancel</a>
    </form>
</section>

<?php require_once './includes/footer.php'; ?>
