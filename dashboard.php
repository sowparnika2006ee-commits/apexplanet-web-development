<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : "";

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT * FROM posts
        WHERE title LIKE '%$search%'
        OR content LIKE '%$search%'
        ORDER BY id DESC
        LIMIT $start, $limit";

$result = mysqli_query($conn, $sql);

$total_query = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM posts
 WHERE title LIKE '%$search%'
 OR content LIKE '%$search%'");

$total_row = mysqli_fetch_assoc($total_query);
$total_pages = ceil($total_row['total'] / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h2 class="mb-3">Welcome <?php echo $_SESSION['user']; ?></h2>

    <a href="create.php" class="btn btn-success">Create Post</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>

    <hr>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search"
                   class="form-control"
                   placeholder="Search posts"
                   value="<?php echo $search; ?>">

            <button type="submit" class="btn btn-primary">
                Search
            </button>
        </div>
    </form>

    <h3>All Posts</h3>

    <?php
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <div class="card mb-3">
            <div class="card-body">

                <h4><?php echo $row['title']; ?></h4>

                <p><?php echo $row['content']; ?></p>

                <a href="edit.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-warning">
                    Edit
                </a>

                <a href="delete.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-danger">
                    Delete
                </a>

            </div>
        </div>
    <?php
    }
    ?>

    <nav>
        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?page=$i&search=$search' class='btn btn-secondary m-1'>$i</a>";
        }
        ?>
    </nav>

</div>

</body>
</html>