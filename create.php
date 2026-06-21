<?php
include "db.php";

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    mysqli_query($conn, "INSERT INTO posts (title, content) VALUES ('$title', '$content')");
    header("Location: dashboard.php");
}
?>

<h2>Create Post</h2>

<form method="POST">
    <input name="title" placeholder="Title"><br><br>
    <textarea name="content" placeholder="Content"></textarea><br><br>
    <button name="submit">Save</button>
</form>