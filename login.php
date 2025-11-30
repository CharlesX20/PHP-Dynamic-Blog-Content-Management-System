<?php
// Start session for auth
use classes\Database;

session_start();

// If already logged in, send to dashboard
if (!empty($_SESSION['user_id'])) {
    header('Location: content.php');
    exit;
}

// Page metadata
$pageTitle = "Log In";
$pageDesc  = "Enter your credentials to access your account";

//requires
require_once './includes/header.php';
require_once './classes/Database.php';
require_once './classes/Validate.php';

$db = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $email    = Validate::sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate email & password presence
    if (!Validate::validateEmail($email)) {
        die('Please enter a valid email address.');
    }
    if (empty($password)) {
        die('Please enter your password.');
    }

    // Fetch user by email
    $stmt = $db->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
    $stmt->execute([ $email ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Credentials ok—set session and redirect
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['flash_success'] = 'Logged in successfully. Welcome back, ' . htmlspecialchars($user['name']) . '!';
        header('Location: content.php');
        exit;
    } else {
        $error = 'Email or password is incorrect.';
    }
}
?>

    <!-- Page header -->
    <section class="lesson-masthead">
        <h1>Log In to Tech‑Tok</h1>
    </section>

    <!-- Login form -->
    <section class="add-form-row">
        <?php if (!empty($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label class="form-label">Email:</label><br>
            <input class="form-control" type="email" name="email" required><br><br>

            <label class="form-label">Password:</label><br>
            <input class="form-control" type="password" name="password" required><br><br>

            <button class="btn btn-primary" type="submit">Log In</button>
        </form>
    </section>

<?php
require_once './includes/footer.php';
?>
