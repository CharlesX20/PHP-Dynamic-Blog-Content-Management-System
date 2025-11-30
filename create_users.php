<?php

use classes\Database;

session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
//page metadata
$pageTitle       = "Create New User";
$pageDescription = "Admin page for creating new user accounts";

//requires
require_once './includes/header.php';
require_once './classes/Database.php';
require_once './classes/UserCrud.php';
require_once './classes/Validate.php';

$db      = (new Database())->getConnection();
$user    = new UserCrud($db);

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = $_POST['name'] ?? '';
    $email            = $_POST['email'] ?? '';
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $avatar           = '';

    // 1) Basic validations using our validate class and functions
    if (!Validate::validateName($name)) {
        $error = "Name must be at least 3 letters and only contain letters, spaces or hyphens.";
    } elseif (!Validate::validateEmail($email)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }

    // 2) Avatar upload logic (only if no error and correct file was submitted)
    if (empty($error) && !empty($_FILES['avatar']['name'])) {
        // generate a unique filename
        $avatar = time() . '_' . basename($_FILES['avatar']['name']);
        $targetDir  = 'uploads/';
        $targetFile = $targetDir . $avatar;

        // check if the file is actually an image
        $check = getimagesize($_FILES['avatar']['tmp_name']);
        if ($check === false) {
            $error = "File is not a valid image.";
        }
        // attempt to move the uploaded file
        elseif (!move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
            $error = "Sorry, there was an error uploading your avatar.";
        }
    }

    // 3) Duplicate‑email check!
    if (empty($error)) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $error = "That email is already registered.";
        }
    }

    // 4) Create user if no errors
    if (empty($error)) {
        $user->name     = $name;
        $user->email    = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->avatar   = $avatar;           // ← pass the uploaded filename into your model

        if ($user->create()) {
            $_SESSION['flash_success'] = "User created successfully!";
            header('Location: users.php');
            exit;
        } else {
            $error = "Registration Error - could not create user.";
        }
    }
}
?>

<section class="lesson-masthead">
    <h1>Create New User</h1>
</section>

<section class="add-form-row">
    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="register-form">
        <label class="form-label">Name:</label><br>
        <input class="form-control" type="text" name="name"
               value="<?= htmlspecialchars($name ?? '') ?>" required><br><br>

        <label class="form-label">Email:</label><br>
        <input class="form-control" type="email" name="email"
               value="<?= htmlspecialchars($email ?? '') ?>" required><br><br>

        <label class="form-label">Password:</label><br>
        <input class="form-control" type="password" name="password" required><br><br>

        <label class="form-label">Confirm Password:</label><br>
        <input class="form-control" type="password" name="confirm_password" required><br><br>

        <label class="form-label">Avatar (optional):</label><br>
        <input class="form-control" type="file" name="avatar" accept="image/*"><br><br>

        <button class="btn btn-primary" type="submit">Register Now</button>
        <a href="users.php" class="btn btn-danger">Cancel</a>
    </form>
</section>

<?php require_once './includes/footer.php'; ?>
