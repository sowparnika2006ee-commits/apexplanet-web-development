<?php
session_start();
include "db.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid request!");
}

$id = $_GET['id'];

// Get post details using prepared statement
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Post not found!");
}

$post = $result->fetch_assoc();

$message = "";

if (isset($_POST['update'])) {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Server-side validation
    if (empty($title) || empty($content)) {
        $message = "All fields are required!";
    } else {

        // Update using prepared statement
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Error updating post!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
</head>
<body>

<h2>Edit Post</h2>

<form method="POST">

    <input
        type="text"
        name="title"
        value="<?php echo htmlspecialchars($post['title']); ?>"
        required>

    <br><br>

    <textarea
        name="content"
        required><?php echo htmlspecialchars($post['content']); ?></textarea>

    <br><br>

    <button type="submit" name="update">Update</button>

</form>

<p><?php echo $message; ?></p>

</body>
</html>