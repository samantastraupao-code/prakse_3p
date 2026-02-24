<?php 
require_once("../connection.php");

// Fetch all genres
$query = "SELECT * FROM genres";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Genres</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin Panel</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="../movie/movie.php">Movie</a></li>
        <li class="nav-item"><a class="nav-link" href="../actors/actors.php">Actors</a></li>
        <li class="nav-item"><a class="nav-link active" href="genre.php">Genre</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Žanri</h1>
        <a href="add_genre.php" class="btn btn-success">Add New Genre</a>
    </div>

    <table class="table table-bordered table-dark text-light">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nosaukums</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['genre_id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                    <a href="edit_genre.php?genre_id=<?php echo $row['genre_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                </td>
                <td>
                    <a href="delete_genre.php?Del=<?php echo $row['genre_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Vai tiešām dzēst šo žanru?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>