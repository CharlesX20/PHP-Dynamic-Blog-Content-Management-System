<?php
// start session for auth checks
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// allow pages to override title/description
$pageTitle = $pageTitle ?? 'CharlesGPT Tech‑Tok';
$pageDesc  = $pageDesc  ?? 'A modern content platform for creators and bloggers.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex,nofollow">
    <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- main stylesheet (Note: I did not use any bootstrap, because I like doing the styling myself)-->
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
<header>
    <nav>
        <div class="logo-container">
            <a href="index.php" class="logo">
                <img src="./images/mylogo.png" alt="Charles4pf Tech‑Tok logo" />
            </a>
        </div>

        <div class="hamburger">
            <i class="fas fa-bars"></i>
        </div>

        <ul class="nav-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="index.php#latest">Latest Stories</a></li>

            <?php if (empty($_SESSION['user_id'])): ?>
                <li><a href="register.php" class="register-link">Get Started Free</a></li>
            <?php else: ?>
                <li><a href="content.php">My Posts</a></li>
                <li><a href="users.php">Users</a></li>
            <?php endif; ?>
                <!-- Show the login form in header then, if logged in show the users name and log out button-->
            <li class="nav-auth">
                <?php if (empty($_SESSION['user_id'])): ?>
                    <form action="login.php" method="post" class="login-form">
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit">Log In</button>
                    </form>
                <?php else: ?>
                    <span class="welcome">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <a href="logout.php" class="register-link">Log Out</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</header>
<main>
    <!--For login success message-->
    <?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success">
      <?= $_SESSION['flash_success'] ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
  <?php endif; ?>