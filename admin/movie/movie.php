<?php
require_once("../connection.php"); 

// Visi filmas un aktieru dati, savieno vienā tabulā
$query = "
SELECT 
    m.movie_id, m.title, m.description, m.release_year, m.duration, m.image,
    g.name AS genre_name,
    a.first_name, a.last_name
FROM movies m
LEFT JOIN genres g ON m.genre_id = g.genre_id
LEFT JOIN movie_actors ma ON m.movie_id = ma.movie_id
LEFT JOIN actors a ON ma.actor_id = a.actor_id
ORDER BY m.movie_id
";
$result = mysqli_query($con, $query);

// Sagrupē aktierus un filmas
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
            'genre' => $row['genre_name'],
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
<style>
    
    .description-cell {
        max-width: 500px;
        word-wrap: break-word;
        white-space: normal;
    }

    .movie-image {
        width: 120px;
        height: auto;
    }

    .table-responsive {
        overflow-x: auto;
    }
</style>
</head>
<body class="bg-dark text-light">

<!-- Navigācija -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
    <a class="navbar-brand" href="../dashboard.php">Admin Panel</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link active" href="movie.php">Movies</a></li>
            <li class="nav-item"><a class="nav-link" href="../actors/actors.php">Actors</a></li>
            <li class="nav-item"><a class="nav-link" href="../genre/genre.php">Genres</a></li>
            <li class="nav-item"><a class="nav-link" href="../users/users.php">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="../review/review.php">Review</a></li>
        </ul>
        <a href="../../logout.php" class="btn btn-secondary">Logout</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <h1> Movie list </h1>
        <a href="add_movie.php" class="btn btn-success"> Add a new movie </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-dark text-light align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Year</th>
                    <th>Duration</th>
                    <th>Genre</th>
                    <th>Actors</th>

                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movies as $id => $movie): ?>
                <tr>
                    <td><?php echo $id; ?></td>

                    <td>
                        <?php if(!empty($movie['image']) && file_exists("../../uploads/".$movie['image'])): ?>
                            <img src="../../uploads/<?php echo htmlspecialchars($movie['image']); ?>" 
                                 alt="Movie Image" class="movie-image">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>

                    <td><?php echo htmlspecialchars($movie['title']); ?></td>

                    <td class="description-cell"><?php echo htmlspecialchars($movie['description']); ?></td>

                    <td><?php echo $movie['release_year']; ?></td>
                    <td><?php echo $movie['duration']; ?></td>
                    <td><?php echo htmlspecialchars($movie['genre']); ?></td>

                    <td>
                        <?php 
                            if (!empty($movie['actors'])) {
                                echo implode(', ', $movie['actors']);
                            } else {
                                echo 'No actors';
                            }
                        ?>
                    </td>
                    <td>
                        <a href="edit_movie.php?GetID=<?php echo $id; ?>" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                    <td>
                        <a href="delete_movie.php?Del=<?php echo $id; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Do you really want to delete this movie?')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>