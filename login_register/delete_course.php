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

$conn->query("DELETE FROM courses WHERE id = $id");
header("Location: user_page.php");
exit();
?>