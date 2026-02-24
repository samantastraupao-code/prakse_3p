<?php
require_once("../connection.php");

if(!isset($_GET['GetID'])){
    die("No movie selected!");
}

$movie_id = $_GET['GetID'];

// Fetch movie
$movie_query = "SELECT * FROM movies WHERE movie_id='$movie_id'";
$movie_res = mysqli_query($con, $movie_query);
$movie = mysqli_fetch_assoc($movie_res);
if(!$movie) die("Movie not found!");

// Fetch genres and actors
$genre_result = mysqli_query($con, "SELECT * FROM genres");
$actor_result = mysqli_query($con, "SELECT * FROM actors");

// Fetch current actors for this movie
$selected_actors = [];
$actor_query = "SELECT actor_id FROM movie_actors WHERE movie_id='$movie_id'";
$actor_res = mysqli_query($con, $actor_query);
while($row = mysqli_fetch_assoc($actor_res)){
    $selected_actors[] = $row['actor_id'];
}

// Handle form submission
if(isset($_POST['edit'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_year = $_POST['release_year'];
    $duration = $_POST['duration'];
    $genre_id = $_POST['genre_id'];
    $actors = $_POST['actors'] ?? [];

    // Handle image upload
    $image_name = $movie['image']; // keep old if not uploaded
    if(!empty($_FILES['image']['name'])){
        $image_name = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/".$image_name);
        if(file_exists("../../uploads/".$movie['image'])){
            unlink("../../uploads/".$movie['image']); // delete old image
        }
    }

    // Update movie
    $update_query = "UPDATE movies SET 
                        title='$title',
                        description='$description',
                        release_year='$release_year',
                        duration='$duration',
                        genre_id='$genre_id',
                        image='$image_name'
                     WHERE movie_id='$movie_id'";
    mysqli_query($con, $update_query);

    // Update movie_actors table
    mysqli_query($con, "DELETE FROM movie_actors WHERE movie_id='$movie_id'");
    foreach($actors as $actor_id){
        mysqli_query($con, "INSERT INTO movie_actors (movie_id, actor_id) VALUES ('$movie_id', '$actor_id')");
    }

    header("Location: movie.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Movie</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card mt-5">
                <div class="card-header bg-success text-center">Edit Movie</div>
                <div class="card-body">
                    <form action="edit_movie.php?GetID=<?php echo $movie_id ?>" method="post" enctype="multipart/form-data">

                        <!-- Title -->
                        <input type="text" class="form-control mb-2" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>

                        <!-- Description -->
                        <textarea class="form-control mb-2" name="description" required><?php echo htmlspecialchars($movie['description']); ?></textarea>

                        <!-- Release Year -->
                        <input type="number" class="form-control mb-2" name="release_year" value="<?php echo $movie['release_year']; ?>" min="1800" max="2100" required>

                        <!-- Duration -->
                        <input type="number" class="form-control mb-2" name="duration" value="<?php echo $movie['duration']; ?>" min="1" required>

                        <!-- Genre -->
                        <select name="genre_id" class="form-control mb-2" required>
                            <option value="">-- Select Genre --</option>
                            <?php
                            mysqli_data_seek($genre_result, 0);
                            while($genre = mysqli_fetch_assoc($genre_result)): ?>
                                <option value="<?php echo $genre['genre_id']; ?>" <?php if($genre['genre_id']==$movie['genre_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($genre['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <!-- Actors -->
                        <div class="border p-2 mb-2" style="max-height:200px; overflow-y:auto;">
                        <?php
                        mysqli_data_seek($actor_result, 0);
                        while($actor = mysqli_fetch_assoc($actor_result)): ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="actors[]" value="<?php echo $actor['actor_id']; ?>"
                                    <?php if(in_array($actor['actor_id'], $selected_actors)) echo 'checked'; ?>>
                                <label class="form-check-label"><?php echo htmlspecialchars($actor['first_name'].' '.$actor['last_name']); ?></label>
                            </div>
                        <?php endwhile; ?>
                        </div>

                        <!-- Current Image -->
                        <div class="mb-2">
                            <label>Current Image:</label><br>
                            <?php if(!empty($movie['image']) && file_exists("../../uploads/".$movie['image'])): ?>
                                <img src="../../uploads/<?php echo $movie['image']; ?>" width="120">
                            <?php else: ?>
                                <p>No image uploaded</p>
                            <?php endif; ?>
                        </div>

                        <!-- Upload New Image -->
                        <input type="file" class="form-control mb-2" name="image">

                        <button class="btn btn-primary w-100" name="edit">Update Movie</button>
                    </form>

                    <a href="movie.php" class="btn btn-secondary w-100 mt-2">Back to Movies</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>