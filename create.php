<?php
session_start();
include "db.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['submit'])) {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Server-side validation
    if (empty($title) || empty($content)) {
        $message = "All fields are required!";
    } else {

        // Prepared statement
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Error saving post!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
</head>
<body>

<h2>Create Post</h2>

<form method="POST">

    <input type="text"
           name="title"
           placeholder="Title"
           required>

    <br><br>

    <textarea name="content"
              placeholder="Content"
              required></textarea>

    <br><br>

    <button type="submit" name="submit">Save</button>

</form>

<p><?php echo $message; ?></p>

</body>
</html>