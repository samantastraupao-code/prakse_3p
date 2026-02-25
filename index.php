<?php
session_start();
require_once("admin/connection.php");

// savāc filmas, aktiera uz žanra datus
$query = "
SELECT 
    m.movie_id,
    m.title,
    m.description,
    m.release_year,
    m.duration,
    m.image,
    g.name AS genre_name,
    a.first_name,
    a.last_name
FROM movies m
LEFT JOIN genres g ON m.genre_id = g.genre_id
LEFT JOIN movie_actors ma ON m.movie_id = ma.movie_id
LEFT JOIN actors a ON ma.actor_id = a.actor_id
ORDER BY m.movie_id DESC
";

$result = mysqli_query($con, $query);

// Sagrupē aktierus pa filmām
$movies = [];

while($row = mysqli_fetch_assoc($result)) {

    $id = $row['movie_id'];

    if(!isset($movies[$id])) {
        $movies[$id] = [
            'title' => $row['title'],
            'description' => $row['description'],
            'release_year' => $row['release_year'],
            'duration' => $row['duration'],
            'image' => $row['image'],
            'genre_name' => $row['genre_name'],
            'actors' => []
        ];
    }

    if($row['first_name']) {
        $movies[$id]['actors'][] = 
            $row['first_name'] . ' ' . $row['last_name'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ratemovie</title>

<style>

</style>

</head>
<body>

<h1>Welcome to Ratemovie 🎬</h1>
<h2>Movies</h2>

<div class="topnav">
  <a href="index.php">Sākums</a>
  <a href="login.php">Ielogoties</a>
  <a href="register.php">Reģistrēties</a>
  <a class= "active" href="profile.php">profils</a>
</div>

<?php foreach($movies as $movie): ?>

<div class="movie_box">

    <div>
        <img src="uploads/<?php echo htmlspecialchars($movie['image']); ?>" width="150">
    </div>

    <div><strong>Title:</strong>
        <?php echo htmlspecialchars($movie['title']); ?>
    </div>

    <div><strong>Genre:</strong>
        <?php echo htmlspecialchars($movie['genre_name']); ?>
    </div>

    <div><strong>Release Year:</strong>
        <?php echo $movie['release_year']; ?>
    </div>

    <div><strong>Duration:</strong>
        <?php echo $movie['duration']; ?> min
    </div>

    <div><strong>Actors:</strong>
        <?php
        if(!empty($movie['actors'])) {
            echo implode(", ", $movie['actors']);
        } else {
            echo "No actors";
        }
        ?>
    </div>

    <div><strong>Description:</strong>
        <?php echo htmlspecialchars($movie['description']); ?>
    </div>

</div>

<?php endforeach; ?>

</body>
</html>
