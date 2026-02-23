<?php
require_once("connection.php");

// Get all movies with their actors in one query
$query = "
SELECT 
    m.movie_id, m.title, m.description, m.release_year, m.duration, m.image,
    a.first_name, a.last_name
FROM movies m
LEFT JOIN movie_actors ma ON m.movie_id = ma.movie_id
LEFT JOIN actors a ON ma.actor_id = a.actor_id
ORDER BY m.movie_id
";
$result = mysqli_query($con, $query);

// Group actors by movie in PHP
$movies = [];
while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['movie_id'];
    if (!isset($movies[$id])) {
        $movies[$id] = [
            'title' => $row['title'],
            'description' => $row['description'],
            'release_year' => $row['release_year'],
            'duration' => $row['duration'],
            'image' => $row['image'],
            'actors' => []
        ];
    }

    if ($row['first_name'] && $row['last_name']) {
        $movies[$id]['actors'][] = $row['first_name'] . ' ' . $row['last_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Filmu saraksts</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<nav>
  <a href="movie.php"> Movie</a> 
  <a href="actors.php"> Actors</a> 
  <a href="genre.php">Genre</a> 
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Filmu saraksts</h1>
    <table class="table table-bordered table-dark">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nosaukums</th>
                <th>Apraksts</th>
                <th>Gads</th>
                <th>Garums</th>
                <th>Attēls</th>
                <th>Aktieri</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>

<a href="../logout.php" class="btn btn-secondary">Izrakstīties</a>

        </thead>
        <tbody>
            <?php foreach ($movies as $id => $movie): ?>
            <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo htmlspecialchars($movie['title']); ?></td>
                <td><?php echo htmlspecialchars($movie['description']); ?></td>
                <td><?php echo $movie['release_year']; ?></td>
                <td><?php echo $movie['duration']; ?></td>
                <td>
                    <img src="../uploads/<?php echo htmlspecialchars($movie['image']); ?>" width="120" alt="Movie Image">
                </td>
                <td>
                    <?php 
                        if (!empty($movie['actors'])) {
                            echo implode(', ', $movie['actors']);
                        } else {
                            echo 'Nav aktieru';
                        }
                    ?>
                </td>
                <td>
                    <a href="edit.php?GetID=<?php echo $id; ?>" class="btn btn-warning btn-sm">Edit</a>
                </td>
                <td>
                    <a href="delete_movie.php?Del=<?php echo $id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Vai tiešām dzēst šo filmu?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
</div>

</body>
</html>