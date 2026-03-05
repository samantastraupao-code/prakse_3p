<?php
session_start();
require_once("admin/connection.php");

if(!$con){
    die("Database connection failed: " . mysqli_connect_error());
}

/* Saglabā atsauksmi */
if(isset($_POST['submit_review'])){

/* Ja lietotājs nav pierakstījies lapā, nevar atstāt atsauksmi */
    if(!isset($_SESSION['username'])){
        die("You must be logged in.");
    }

    $movie_id = intval($_POST['movie_id']);
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    $username = $_SESSION['username'];

    $user_query = "SELECT user_id FROM users WHERE username='$username' LIMIT 1";
    $user_result = mysqli_query($con, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);
    $user_id = $user_data['user_id'];

    /* Ja lietotājs atstāj atsauksmi, to ievieto datubāzē */
    if($rating > 0){
        $insert = "INSERT INTO reviews (movie_id, user_id, rating, comment)
                   VALUES ('$movie_id', '$user_id', '$rating', '$comment')";
        mysqli_query($con, $insert);

        header("Location: index.php");
        exit();
    }
}

/* Apvieno tabulas*/
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

$movies = [];

while($row = mysqli_fetch_assoc($result)) {

    $id = $row['movie_id'];

    if(!isset($movies[$id])) {
        $movies[$id] = [
            'movie_id' => $id,
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
<link rel="stylesheet" href="css/style_index.css">

</head>
<body>

    <div class="header">
        <h1>Welcome to Ratemovie</h1>
    </div>

    <div class="topnav">
        <?php if(isset($_SESSION['username'])): ?>
            Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            <a href="logout.php" class="logout_btn">Logout</a>
        <?php else: ?>
            <a href="login.php">Log in</a>
            <a href="register.php">Register</a>
        <?php endif; ?> 
    </div>

    <div class="second_nav">
        <h2> <a href="actors_user.php">Actors</a></h2>
        <h2> <a href="index.php">Movies</a></h2>
    </div>

    <div class="movies_container">

        <?php foreach($movies as $movie): ?>

        <!-- Atsauksmes -->
        <?php
        $movie_id = $movie['movie_id'];
        $review_query = "
            SELECT r.rating, r.comment, u.username
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            WHERE r.movie_id = $movie_id
            ORDER BY r.created_at DESC
        ";
        $review_result = mysqli_query($con, $review_query);

        $reviews = [];
        $total_rating = 0;
        $review_count = mysqli_num_rows($review_result);

        while($r = mysqli_fetch_assoc($review_result)){
            $reviews[] = $r;
            $total_rating += $r['rating'];
        }
        $avg_rating = $review_count ? round($total_rating / $review_count,1) : 0;
        ?>

        <div class="movie_box">

            <div class="image">
                <img src="uploads/<?php echo htmlspecialchars($movie['image']); ?>" width="200">
            </div>

            <div class="title">
                <strong>Title:</strong> 
                <?php echo htmlspecialchars($movie['title']); ?>
            </div>

            <div class="average_r">
                <strong>Average Rating:</strong> 
                <?php echo $avg_rating; ?> / 5 <br> (<?php echo $review_count; ?> reviews)
            </div>

            <div class="genre">
                <strong>Genre:</strong> 
                <?php echo htmlspecialchars($movie['genre_name']); ?>
            </div>

            <div class="year">
                <strong>Release Year:</strong> 
                <?php echo $movie['release_year']; ?>
            </div>

            <div class="duration">
                <strong>Duration:</strong> 
                <?php echo $movie['duration']; ?> min
            </div>

            <div class="actor">
                <strong>Actors:</strong> 
                <?php echo !empty($movie['actors']) ? implode(", ", $movie['actors']) : "No actors listed"; ?>
            </div>

            <div class="description">
                <strong>Description:</strong> 
                <?php echo htmlspecialchars($movie['description']); ?>
            </div>

            <button class="toggle-reviews" data-movie-id="<?php echo $movie_id; ?>">
                Show Reviews (<?php echo $review_count; ?>)
            </button>
        <!-- Atsauksmju konteineris -->
            <div class="reviews-container" id="reviews-<?php echo $movie_id; ?>" style="display:none;">
                <?php if($review_count > 0): ?>
                    <?php foreach($reviews as $rev): ?>
                        <div>
                            <strong><?php echo htmlspecialchars($rev['username']); ?></strong>
                            rated: <?php echo $rev['rating']; ?>/5
                            <br>
                            <?php echo htmlspecialchars($rev['comment']); ?>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet</p>
                <?php endif; ?>
            </div>

            <?php if(isset($_SESSION['username'])): ?>

            <!-- Atsauksmju forma(lietotājs atstāj atsauksmi par filmu) -->
            <div class="review">
                <form method="POST">
                    <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                    <input type="hidden" name="rating" class="rating-value" value="0">

                    <div class="stars">
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="rating-text">0/5</span>
                    </div>

                    <textarea name="comment" placeholder="Write comment..." required></textarea>
                    <br>
                    <button type="submit" name="submit_review">Submit Review</button>
                </form>
            </div>
            <?php else: ?>

            <!-- Ziņojums, jābūt pierakstītam mājaslapā, lai atstātu atsauksmi -->
            <div class="review review-guest">
                You need to <a href="login.php">log in</a> to leave a review.
            </div>
            <?php endif; ?>

        </div>

        <?php endforeach; ?>

    </div>

            <!-- JS kods, zvaigžņu vērtēšanas sistēma -->
    <script>
    document.addEventListener('DOMContentLoaded', function(){

        document.querySelectorAll('.stars').forEach(function(container){
            let stars = container.querySelectorAll('.star');
            let text = container.querySelector('.rating-text');
            let input = container.closest('form').querySelector('.rating-value');

            stars.forEach(function(star, index){
                star.addEventListener('click', function(){
                    stars.forEach((s,i)=> s.classList.toggle('rated', i <= index));
                    let rating = index + 1;
                    text.textContent = rating + "/5";
                    input.value = rating;
                });
            });
        });

        document.querySelectorAll('.toggle-reviews').forEach(function(btn){
            let id = btn.dataset.movieId;
            let container = document.getElementById('reviews-' + id);

            btn.addEventListener('click', function(){
                container.style.display =
                    container.style.display === 'none' ? 'block' : 'none';
            });
        });

    });
    </script>

</body>
</html>