<?php
session_start();
if (!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: user_page.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn->query("DELETE FROM courses WHERE id = $id AND user_id = $user_id");
header("Location: user_page.php");
exit();
?>