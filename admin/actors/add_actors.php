<?php
require_once("../connection.php");

// Handle form submission
if(isset($_POST['submit'])) {

    // Check required fields
    if(empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['birth_date']) || empty($_FILES['photo']['name'])) {
        $error = "Please fill in all the fields and select a photo.";
    } else {
        $first_name = $_POST['first_name'];
        $last_name  = $_POST['last_name'];
        $birth_date = $_POST['birth_date'];

        // Handle photo upload
        $photo_name = $_FILES['photo']['name'];
        $photo_tmp  = $_FILES['photo']['tmp_name'];

        // Move the uploaded file to uploads folder
        $upload_dir = "../uploads/";
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0755, true); // create uploads folder if it doesn't exist
        }

        move_uploaded_file($photo_tmp, $upload_dir.$photo_name);

        // Insert into database
        $query = "INSERT INTO actors (first_name, last_name, birth_date, photo, created_at) 
                  VALUES ('$first_name', '$last_name', '$birth_date', '$photo_name', NOW())";
        $result = mysqli_query($con, $query);

        if($result){
            header("Location: actors.php");
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
<title>Add Actor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card mt-3">
                <div class="card-header bg-success text-white text-center">
                    <h3>Add New Actor</h3>
                </div>
                <div class="card-body">

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="add_actors.php" method="post" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Birth Date</label>
                            <input type="date" name="birth_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*" required>
                        </div>

                        <button type="submit" name="submit" class="btn btn-success w-100">Add Actor</button>

                    </form>

                    <a href="actors.php" class="btn btn-secondary mt-3 w-100">Back to Actors List</a>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>