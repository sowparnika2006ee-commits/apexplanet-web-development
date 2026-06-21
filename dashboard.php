<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
?>

<h2>Welcome <?php echo $_SESSION['user']; ?></h2>

<a href="create.php">Create Post</a> |
<a href="logout.php">Logout</a>

<hr>

<h3>All Posts</h3>

<?php
$result = mysqli_query($conn, "SELECT * FROM posts ORDER BY id DESC");

while ($row = mysqli_fetch_assoc($result)) {
    echo "<h3>".$row['title']."</h3>";
    echo "<p>".$row['content']."</p>";
    echo "<a href='edit.php?id=".$row['id']."'>Edit</a> | ";
    echo "<a href='delete.php?id=".$row['id']."'>Delete</a><hr>";
}
?>