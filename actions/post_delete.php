<?php
// Protect page
use classes\Database;

session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Imports
require '../classes/Database.php';
require '../classes/ContentCrud.php';

// Initialize
$db   = (new Database())->getConnection();
$post = new ContentCrud($db);

// Ensure we have an ID
if (empty($_GET['id'])) {
    die('<p>Post ID not specified.</p>');
}
$post->id = $_GET['id'];

// Delete and redirect
if ($post->delete()) {
    header('Location: ../content.php');
    exit;
} else {
    echo 'Error deleting post.';
}
?>
