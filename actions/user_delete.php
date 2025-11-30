<?php
// Protect page
use classes\Database;

session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require '../classes/Database.php';
require '../classes/UserCrud.php';

$db   = (new Database())->getConnection();
$user = new UserCrud($db);

if (empty($_GET['id'])) {
    die('<p>User ID not specified.</p>');
}
$user->id = (int) $_GET['id'];

//delete and redirect
if ($user->delete()) {
    header('Location: ../users.php');
    exit;
} else {
    die('Error deleting user.');
}

?>