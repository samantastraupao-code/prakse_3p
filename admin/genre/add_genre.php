<?php
require_once("../connection.php");

// Handle form submission
if(isset($_POST['submit'])){
    if(empty($_POST['name'])){
        $error = "Please enter a genre name.";
    } else {
        $name = $_POST['name'];
        $query = "INSERT INTO genres (name) VALUES ('$name')";
        $result = mysqli_query($con, $query);

        if($result){
            header("Location: ./genre.php");
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
<title>Add Genre</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card mt-3">
                <div class="card-header bg-success text-white text-center">
                    <h3>Add New Genre</h3>
                </div>
                <div class="card-body">

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="./add_genre.php" method="post">
                        <div class="mb-3">
                            <label>Genre Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-success w-100">Add Genre</button>
                    </form>

                    <a href="./genre.php" class="btn btn-secondary mt-3 w-100">Back to Genres List</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>