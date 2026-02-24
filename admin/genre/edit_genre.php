<?php
require_once("../connection.php");

// ---------- HANDLE FORM SUBMISSION ----------
if(isset($_POST['edit'])) {
    if(!isset($_GET['genre_id'])) {
        echo "No genre selected!";
        exit();
    }

    $genre_id = $_GET['genre_id'];
    $name = trim($_POST['name']);

    if($name == "") {
        echo "Genre name cannot be empty!";
        exit();
    }

    $update = "UPDATE genres SET name='$name' WHERE genre_id='$genre_id'";
    if(mysqli_query($con, $update)) {
        header("Location: genre.php");
        exit();
    } else {
        echo "Database error: " . mysqli_error($con);
        exit();
    }
}

// ---------- LOAD GENRE DATA ----------
if(!isset($_GET['genre_id'])) {
    echo "No genre selected!";
    exit();
}

$genre_id = $_GET['genre_id'];
$query = "SELECT * FROM genres WHERE genre_id='$genre_id'";
$result = mysqli_query($con, $query);

if(!$row = mysqli_fetch_assoc($result)) {
    echo "Genre not found!";
    exit();
}

$genre_name = $row['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Genre</title>
    <link rel="stylesheet" href="CSS/bootstrap.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<div class="container">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card mt-5">
                <div class="card-title">
                    <h3 class="bg-success text-white text-center py-3">Edit Genre</h3>
                </div>
                <div class="card-body">

                    <form action="edit_genre.php?genre_id=<?php echo $genre_id ?>" method="post">
                        <!-- Genre Name -->
                        <input type="text" 
                               class="form-control mb-2" 
                               placeholder="Genre Name" 
                               name="name" 
                               value="<?php echo htmlspecialchars($genre_name); ?>" 
                               required>

                        <!-- Submit Button -->
                        <button class="btn btn-primary w-100" name="edit">Update Genre</button>
                    </form>

                    <a href="genre.php" class="btn btn-secondary mt-3 w-100">Back to Genre List</a>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>