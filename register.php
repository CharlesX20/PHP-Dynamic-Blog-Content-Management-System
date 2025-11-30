<?php
// start session so we can auto‑login after registration
use classes\Database;

session_start();

// if already logged in, send to dashboard
if (!empty($_SESSION['user_id'])) {
    header('Location: content.php');
    exit;
}

// page metadata
$pageTitle = "Register";
$pageDesc  = "Create your free account on Tech‑Tok";

//requires
require_once './includes/header.php';
require_once './classes/Database.php';
require_once './classes/UserCrud.php';
require_once './classes/Validate.php';

$db    = (new Database())->getConnection();
$user  = new UserCrud($db);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize inputs
    $name            = Validate::sanitize($_POST['name'] ?? '');
    $email           = Validate::sanitize($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // validate
    if (!Validate::validateName($name)) {
        $error = 'Name must be at least 3 letters and only contain letters, spaces or hyphens.';
    } elseif (!Validate::validateEmail($email)) {
        $error = 'Please enter a valid email address.';
    } elseif (!Validate::validatePassword($password)) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    }

    //Check for duplicate emails!
    if (empty($error)) {
        $check = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $check->execute([ $email ]);
        if ($check->fetchColumn() > 0) {
            $error = 'That email is already registered.';
        }
    }

    // avatar upload
    $avatarName = null;
    if (empty($error) && !empty($_FILES['avatar']['name'])) {
        if (!Validate::validateImageUpload($_FILES['avatar'])) {
            $error = 'Avatar must be JPG, PNG or GIF under 2MB.';
        } else {
            $avatarName  = time() . '_' . basename($_FILES['avatar']['name']);
            $dest        = './uploads/' . $avatarName;
            move_uploaded_file($_FILES['avatar']['tmp_name'], $dest);
        }
    }

    // create user if there's no error
    if (empty($error)) {
        $user->name     = $name;
        $user->email    = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->avatar   = $avatarName;

        if ($user->create()) {
            // auto‑login
            $stmt = $db->prepare("SELECT id, name FROM users WHERE email = ?");
            $stmt->execute([ $email ]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['user_name'] = $row['name'];

            header('Location: content.php');
            exit;
        } else {
            $error = 'Error creating account. Please try again.';
        }
    }
}
?>

    <!-- Page header -->
    <section class="lesson-masthead">
        <h1>Create Your Free Account</h1>
    </section>

    <!-- Registration form -->
    <section class="add-form-row">
        <?php if (!empty($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="register.php" enctype="multipart/form-data" class="register-form">
            <label class="form-label">Name:</label><br>
            <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required><br><br>

            <label class="form-label">Email:</label><br>
            <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required><br><br>

            <label class="form-label">Password:</label><br>
            <input class="form-control" type="password" name="password" required><br><br>

            <label class="form-label">Confirm Password:</label><br>
            <input class="form-control" type="password" name="confirm_password" required><br><br>

            <label class="form-label">Avatar (optional):</label><br>
            <input class="form-control" type="file" name="avatar" accept="image/*"><br><br>

            <button class="btn btn-primary" type="submit">Register Now</button>
        </form>
    </section>

<?php
require_once './includes/footer.php';
