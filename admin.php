<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    die("Access Denied!");
}
?>

<h2>Admin Panel</h2>
<p>Welcome Admin!</p>