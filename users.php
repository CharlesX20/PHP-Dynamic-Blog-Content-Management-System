<?php
// Start session and protect page
use classes\Database;

session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Page metadata for header
$pageTitle = "User Management";
$pageDesc  = "View, edit, or delete registered users";

//requires
require_once './includes/header.php';
// Bring in Database and User CRUD class
require_once './classes/Database.php';
require_once './classes/UserCrud.php';

// Get PDO connection and UserCrud instance
$db       = (new Database())->getConnection();
$userCrud = new UserCrud($db);

// Fetch all users
$stmt = $userCrud->readAll();

$currentUserId = $_SESSION['user_id'];
?>

    <!-- Page header -->
    <section class="lesson-masthead">
        <h1>User List</h1>
    </section>

    <!-- Add New User button -->
    <section class="add-user-row">
        <a href="create_users.php" class="btn btn-primary">Add New User</a>
    </section>

    <!-- Users table -->
    <section class="user-list-row">
        <table class="table table-striped align-middle">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Avatar</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>

            <?php while ($user = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?php if ($user['avatar']): ?>
                            <img src="uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="author-avatar">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <a href="update_users.php?id=<?= $user['id'] ?>" class="btn btn-warning">Edit</a>
                        <?php if ($user['id'] !== $currentUserId): ?>
                            <a href="actions/user_delete.php?id=<?= $user['id'] ?>"
                               onclick="return confirm('Delete this user?');"
                               class="btn btn-danger">
                                Delete
                            </a>
                        <?php else: ?>
                            <!-- prevent deleting yourself -->
                            <span class="btn btn-danger disabled" title="You cannot delete your own account">Delete</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </section>

<?php
require_once './includes/footer.php';
