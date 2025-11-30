<?php

use classes\Database;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
//page metadata
$pageTitle       = "Edit User";
$pageDescription = "Update an existing user";

//requires
require_once './includes/header.php';
require_once './classes/Database.php';
require_once './classes/UserCrud.php';
require_once './classes/Validate.php';

$db      = (new Database())->getConnection();
$user    = new UserCrud($db);

$error   = '';
$success = '';

// Ensure ID
if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: users.php');
    exit;
}
$user->id = (int) $_GET['id'];
$data      = $user->readOne();
if (!$data) {
    header('Location: users.php');
    exit;
}

// Pre-fill
$name             = $data['name'];
$email            = $data['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = $_POST['name'] ?? '';
    $email            = $_POST['email'] ?? '';
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $avatar           = '';

    // Validate text
    if (!Validate::validateName($name)) {
        $error = "Name must be ≥3 letters and only contain letters, spaces or hyphens.";
    } elseif (!Validate::validateEmail($email)) {
        $error = "Please enter a valid email address.";
    } elseif ($password !== '' && strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }

    // Image upload
    if (empty($error) && !empty($_FILES['avatar']['name'])) {
        $avatar      = time() . '_' . basename($_FILES['avatar']['name']);
        $target_dir  = "uploads/";
        $target_file = $target_dir . $avatar;

        $check = getimagesize($_FILES['avatar']['tmp_name']);
        if ($check === false) {
            $error = "File is not a valid image.";
        } elseif (!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
            $error = "Sorry, there was an error uploading your avatar.";
        }
    }

    // Check for Duplicate email!
    if (empty($error)) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?");
        $stmt->execute([$email, $user->id]);
        if ($stmt->fetchColumn() > 0) {
            $error = "That email is already in use.";
        }
    }

    // Update user if there's no error
    if (empty($error)) {
        $user->name     = $name;
        $user->email    = $email;
        if ($password !== '') {
            $user->password = password_hash($password, PASSWORD_DEFAULT);
        }
        if ($avatar !== '') {
            $user->avatar = $avatar;
        }

        if ($user->update()) {
            $_SESSION['flash_success'] = "User updated successfully!";
            header('Location: users.php');
            exit;
        } else {
            $error = "Database error: could not update user.";
        }
    }
}
?>

<section class="lesson-masthead">
    <h1>Edit User</h1>
</section>

<section class="add-form-row">
    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label class="form-label">Name:</label><br>
        <input class="form-control" type="text" name="name"
               value="<?= htmlspecialchars($name) ?>" required><br><br>

        <label class="form-label">Email:</label><br>
        <input class="form-control" type="email" name="email"
               value="<?= htmlspecialchars($email) ?>" required><br><br>

        <label class="form-label">New Password (optional):</label><br>
        <input class="form-control" type="password" name="password"><br><br>

        <label class="form-label">Confirm Password:</label><br>
        <input class="form-control" type="password" name="confirm_password"><br><br>

        <label class="form-label">Avatar (optional):</label><br>
        <input class="form-control" type="file" name="avatar" accept="image/*"><br><br>

        <?php if (!empty($data['avatar'])): ?>
            <p>Current Avatar:</p>
            <img src="uploads/<?= htmlspecialchars($data['avatar']) ?>"
                 alt="Avatar" class="author-avatar"><br><br>
        <?php endif; ?>

        <button class="btn btn-primary" type="submit">Update</button>
        <a class="btn btn-danger" href="users.php">Cancel</a>
    </form>
</section>

<?php require_once './includes/footer.php'; ?>
