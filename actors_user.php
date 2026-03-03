<?php
session_start();
require_once("admin/connection.php");

if(!$con){
    die("Database connection failed: " . mysqli_connect_error());
}

/* Aktieri un filmas kurās ir bijuši*/
$query = "
    SELECT 
        a.actor_id, 
        a.first_name, 
        a.last_name, 
        a.birth_date, 
        a.photo, 
        a.created_at,
        GROUP_CONCAT(m.title SEPARATOR ', ') AS movies
    FROM actors a
    LEFT JOIN movie_actors ma ON a.actor_id = ma.actor_id
    LEFT JOIN movies m ON ma.movie_id = m.movie_id
    GROUP BY a.actor_id
    ORDER BY a.first_name, a.last_name
";

$result = mysqli_query($con, $query);

$actors = [];
while($row = mysqli_fetch_assoc($result)){
    $actors[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RateMovie</title>
 <link rel="stylesheet" href="css/style_actors.css"> 
</head>

<body>

    <div class="header">
        <h1>Welcome to Ratemovie</h1>
    </div>

    <div class="top">
        <?php if(isset($_SESSION['username'])): ?>
            Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Log in</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>

    <div class="second_nav">
        <h2> <a href="actors_user.php">Actors</a></h2>
        <h2> <a href="index.php">Movies</a></h2>
    </div>

    <div class="actors_container">
        <?php foreach($actors as $actor): ?>

        <div class="actor_box">
            <div class="image">
                <?php if(!empty($actor['photo'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($actor['photo']); ?>" alt="Actor Photo">
                <?php else: ?>
                    <img src="uploads\default_actor.png" alt="Actor Photo"> 
                <?php endif; ?>
            </div>

            <div class="actor_full_name">
                <strong><?php echo htmlspecialchars($actor['first_name'] . ' ' . $actor['last_name']); ?></strong>
            </div>

            <div class="birth_date">
                <strong>Birth Date:</strong> <?php echo htmlspecialchars($actor['birth_date']); ?>
            </div>

            <div class="movie_button">
                <button class="toggle_movies" data_actor_id="<?php echo $actor['actor_id']; ?>">
                    Show Movies
                </button>
            </div>

            <div class="movies" id="movies-<?php echo $actor['actor_id']; ?>" style="display:none;">
                <?php echo !empty($actor['movies']) ? htmlspecialchars($actor['movies']) : 'No movies listed'; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".toggle_movies");

        buttons.forEach(function (button) {
            button.addEventListener("click", function () {
                const actorId = this.getAttribute("data_actor_id");
                const moviesDiv = document.getElementById("movies-" + actorId);

                if (moviesDiv.style.display === "none" || moviesDiv.style.display === "") {
                    moviesDiv.style.display = "block";
                } else {
                    moviesDiv.style.display = "none";
                }
            });
        });
    });
    </script>

</body>
</html>