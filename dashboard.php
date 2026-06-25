<?php
session_start();
include "db.php";

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $limit;

$searchTerm = "%" . $search . "%";

// Fetch posts
$stmt = $conn->prepare("
    SELECT * FROM posts
    WHERE title LIKE ?
    OR content LIKE ?
    ORDER BY id DESC
    LIMIT ?, ?
");

$stmt->bind_param("ssii", $searchTerm, $searchTerm, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Count posts
$countStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM posts
    WHERE title LIKE ?
    OR content LIKE ?
");

$countStmt->bind_param("ss", $searchTerm, $searchTerm);
$countStmt->execute();

$total_result = $countStmt->get_result();
$total_row = $total_result->fetch_assoc();

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

    <h2 class="mb-3">
        Welcome <?php echo htmlspecialchars($_SESSION['user']); ?>
    </h2>

    <!-- DEBUG (remove later) -->
    <?php
    // echo "Role: " . ($_SESSION['role'] ?? 'NOT SET');
    ?>

    <a href="create.php" class="btn btn-success">Create Post</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>

    <hr>

    <!-- SEARCH -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Search posts"
                   value="<?php echo htmlspecialchars($search); ?>">

            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    <h3>All Posts</h3>

    <!-- POSTS -->
    <?php while ($row = $result->fetch_assoc()) { ?>

        <div class="card mb-3">
            <div class="card-body">

                <h4><?php echo htmlspecialchars($row['title']); ?></h4>

                <p><?php echo htmlspecialchars($row['content']); ?></p>

                <a href="edit.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-warning">
                    Edit
                </a>

                <!-- DELETE BUTTON FIX -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>

                    <a href="delete.php?id=<?php echo $row['id']; ?>"
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to delete this post?');">
                        Delete
                    </a>

                <?php } ?>

            </div>
        </div>

    <?php } ?>

    <!-- PAGINATION -->
    <nav>
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>

            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
               class="btn btn-secondary m-1">
                <?php echo $i; ?>
            </a>

        <?php } ?>
    </nav>

</div>

</body>
</html>