<?php
require_once("../connection.php");

// Fetch genres for dropdown
$genre_query = "SELECT * FROM genres";
$genre_result = mysqli_query($con, $genre_query);

// Fetch all actors for checkboxes
$actor_query = "SELECT * FROM actors";
$actor_result = mysqli_query($con, $actor_query);

// Handle form submission
if(isset($_POST['submit'])) {

    if(empty($_POST['title']) || empty($_POST['description']) || empty($_POST['release_year']) || empty($_POST['duration']) || empty($_FILES['image']['name']) || empty($_POST['genre_id']) || empty($_POST['actors'])){
        $error = "Please fill in all fields, select actors, and upload an image.";
    } else {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $release_year = $_POST['release_year'];
        $duration = $_POST['duration'];
        $genre_id = $_POST['genre_id'];
        $selected_actors = $_POST['actors']; // array of actor IDs

        // Handle poster image upload
        $image_name = $_FILES['image']['name'];
        $image_tmp  = $_FILES['image']['tmp_name'];
        $upload_dir = "../uploads/";

        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0755, true);
        }

        move_uploaded_file($image_tmp, $upload_dir.$image_name);

        // Insert into movies table
        $query = "INSERT INTO movies (title, description, release_year, duration, image, genre_id, created_at) 
                  VALUES ('$title', '$description', '$release_year', '$duration', '$image_name', '$genre_id', NOW())";
        $result = mysqli_query($con, $query);

        if($result){
            $movie_id = mysqli_insert_id($con);

            // Insert actors into movies_actor table
            foreach($selected_actors as $actor_id){
    mysqli_query($con, "INSERT INTO movie_actors (movie_id, actor_id) VALUES ('$movie_id', '$actor_id')");
}

            header("Location: movie.php");
            exit();
        } else {
            $error = "Database error: please check your query.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Movie</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 m-auto">
            <div class="card mt-3">
                <div class="card-header bg-success text-white text-center">
                    <h3>Add New Movie</h3>
                </div>
                <div class="card-body">

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="add_movie.php" method="post" enctype="multipart/form-data">

                        <!-- Title -->
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>

                        <!-- Release Year -->
                        <div class="mb-3">
                            <label>Release Year</label>
                            <input type="number" name="release_year" class="form-control" min="1900" max="2100" required>
                        </div>

                        <!-- Duration -->
                        <div class="mb-3">
                            <label>Duration (minutes)</label>
                            <input type="number" name="duration" class="form-control" min="1" required>
                        </div>

                        <!-- Genre -->
                        <div class="mb-3">
                            <label>Genre</label>
                            <select name="genre_id" class="form-control" required>
                                <option value="">-- Select Genre --</option>
                                <?php 
                                mysqli_data_seek($genre_result, 0); // reset pointer
                                while($genre = mysqli_fetch_assoc($genre_result)): ?>
                                    <option value="<?php echo $genre['genre_id']; ?>">
                                        <?php echo htmlspecialchars($genre['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Actors -->
                        <div class="mb-3">
                            <label>Actors</label>
                            <div class="border p-2 bg-dark text-light" style="max-height: 200px; overflow-y: auto;">
                                <?php 
                                mysqli_data_seek($actor_result, 0); // reset pointer
                                while($actor = mysqli_fetch_assoc($actor_result)): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="actors[]" value="<?php echo $actor['actor_id']; ?>" id="actor<?php echo $actor['actor_id']; ?>">
                                        <label class="form-check-label" for="actor<?php echo $actor['actor_id']; ?>">
                                            <?php echo htmlspecialchars($actor['first_name'] . ' ' . $actor['last_name']); ?>
                                        </label>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <!-- Poster Image -->
                        <div class="mb-3">
                            <label>Poster Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>

                        <button type="submit" name="submit" class="btn btn-success w-100">Add Movie</button>
                    </form>

                    <a href="movie.php" class="btn btn-secondary mt-3 w-100">Back to Movies List</a>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>