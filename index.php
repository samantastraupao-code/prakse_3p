<?php
session_start();
require_once("admin/connection.php");

// Check database connection
if(!$con){
    die("Database connection failed: " . mysqli_connect_error());
}

/* =========================
   SAVE REVIEW
========================= */
if(isset($_POST['submit_review'])){

    if(!isset($_SESSION['username'])){
        die("You must be logged in.");
    }

    $movie_id = intval($_POST['movie_id']);
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    $username = $_SESSION['username'];

    // Get user_id from database
    $user_query = "SELECT user_id FROM users WHERE username='$username' LIMIT 1";
    $user_result = mysqli_query($con, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);

    $user_id = $user_data['user_id'];

    if($rating > 0){
        $insert = "INSERT INTO reviews (movie_id, user_id, rating, comment)
                   VALUES ('$movie_id', '$user_id', '$rating', '$comment')";
        mysqli_query($con, $insert);

        header("Location: index.php");
        exit();
    }
}

/* =========================
   FETCH MOVIES
========================= */
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
<style>
body { font-family: Arial, sans-serif; padding: 20px; }
.movie_box { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; }
.star{
    color: goldenrod;
    font-size:2rem;
    padding:0.1rem;
    cursor:pointer;
}
.star::before{ content:'\2606'; }
.star.rated::before{ content:'\2605'; }
.rating-text{ margin-left:10px; font-weight:bold; }
.topnav a { margin-right: 10px; }
</style>
</head>
<body>

<h1>Welcome to Ratemovie 🎬</h1>

<div class="topnav">
<?php if(isset($_SESSION['username'])): ?>
    Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
    <a href="logout.php">Logout</a>
<?php else: ?>
    <a href="login.php">Ielogoties</a>
    <a href="register.php">Reģistrēties</a>
<?php endif; ?>
</div>

<h2>Movies</h2>

<?php foreach($movies as $movie): ?>

<div class="movie_box">

    <img src="uploads/<?php echo htmlspecialchars($movie['image']); ?>" width="150">

    <div><strong>Title:</strong> <?php echo htmlspecialchars($movie['title']); ?></div>
    <div><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre_name']); ?></div>
    <div><strong>Release Year:</strong> <?php echo $movie['release_year']; ?></div>
    <div><strong>Duration:</strong> <?php echo $movie['duration']; ?> min</div>
    <div><strong>Actors:</strong> <?php echo !empty($movie['actors']) ? implode(", ", $movie['actors']) : "No actors"; ?></div>
    <div><strong>Description:</strong> <?php echo htmlspecialchars($movie['description']); ?></div>

    <?php
    // Fetch reviews
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

    <div><strong>Average Rating:</strong> <?php echo $avg_rating; ?> / 5 (<?php echo $review_count; ?> reviews)</div>

    <button class="toggle-reviews" data-movie-id="<?php echo $movie['movie_id']; ?>">
        Show Reviews (<?php echo $review_count; ?>)
    </button>

    <div class="reviews-container" id="reviews-<?php echo $movie['movie_id']; ?>" style="display:none; margin-top:10px;">
        <?php if($review_count > 0): ?>
            <?php foreach($reviews as $rev): ?>
                <div>
                    <strong><?php echo htmlspecialchars($rev['username']); ?></strong> rated: <?php echo $rev['rating']; ?>/5
                    <br>
                    <?php echo htmlspecialchars($rev['comment']); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews yet.</p>
        <?php endif; ?>
    </div>

    <?php if(isset($_SESSION['username'])): ?>
    <div class="review">
        <form method="POST">
            <input type="hidden" name="movie_id" value="<?php echo $movie['movie_id']; ?>">
            <input type="hidden" name="rating" class="rating-value" value="0">

            <div class="stars">
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
                <span class="star"></span>
                <span class="rating-text">0/5</span>
            </div>

            <br>
            <textarea name="comment" placeholder="Write your comment..." required></textarea>
            <br><br>
            <button type="submit" name="submit_review">Submit Review</button>
        </form>
    </div>
    <?php else: ?>
        <div>Please <a href="login.php">log in</a> to leave a review.</div>
    <?php endif; ?>

</div>

<?php endforeach; ?>

<script>
document.addEventListener('DOMContentLoaded', function(){

    // STAR RATING
    document.querySelectorAll('.stars').forEach(function(starContainer){
        let stars = starContainer.querySelectorAll('.star');
        let ratingText = starContainer.querySelector('.rating-text');
        let hiddenInput = starContainer.closest('form')?.querySelector('.rating-value');

        stars.forEach(function(star, index){
            star.addEventListener('click', function(){
                stars.forEach((s, i) => s.classList.toggle('rated', i <= index));
                let rating = index + 1;
                ratingText.textContent = rating + "/5";
                if(hiddenInput) hiddenInput.value = rating;
            });
        });
    });

    // TOGGLE REVIEWS
    document.querySelectorAll('.toggle-reviews').forEach(function(btn){
        let movieId = btn.dataset.movieId;
        let container = document.getElementById('reviews-' + movieId);
        let originalText = btn.textContent;

        btn.addEventListener('click', function(){
            if(container.style.display === 'none' || container.style.display === ''){
                container.style.display = 'block';
                btn.textContent = 'Hide Reviews';
            } else {
                container.style.display = 'none';
                btn.textContent = originalText;
            }
        });
    });

});
</script>

</body>
</html>