<?php
require_once("../connection.php"); // connection.php is one level up

// Fetch all actors
$query = "SELECT * FROM actors";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Actors</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
    <a class="navbar-brand" href="../dashboard.php">Admin Panel</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="../movie/movie.php">Movies</a></li>
            <li class="nav-item"><a class="nav-link active" href="actors.php">Actors</a></li>
            <li class="nav-item"><a class="nav-link" href="../genre/genre.php">Genres</a></li>
            <li class="nav-item"><a class="nav-link" href="../users/users.php">Users</a></li>
        </ul>
        <a href="../../logout.php" class="btn btn-secondary">Logout</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Actors</h1>
        <a href="add_actors.php" class="btn btn-success">Add New Actor</a>
    </div>

    <table class="table table-bordered table-dark text-light align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birth Date</th>
                <th>Photo</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['actor_id']; ?></td>
                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                <td><?php echo $row['birth_date']; ?></td>
                <td>
                    <?php if(!empty($row['photo']) && file_exists("../../uploads/".$row['photo'])): ?>
                        <img src="../../uploads/<?php echo htmlspecialchars($row['photo']); ?>" width="120" alt="Actor image">
                    <?php else: ?>
                        <span>No image</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_actor.php?GetID=<?php echo $row['actor_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                </td>
                <td>
                    <a href="delete_actor.php?Del=<?php echo $row['actor_id']; ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Are you sure you want to delete this actor?')">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>