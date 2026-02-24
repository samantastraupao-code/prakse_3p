<?php
require_once("../connection.php");

// ---------- HANDLE FORM SUBMISSION ----------
if(isset($_POST['edit'])) {
    $actor_id = $_GET['GetID']; // matches the form action

    $first_name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];

    // Get current photo from DB
    $check = "SELECT photo FROM actors WHERE actor_id='$actor_id'";
    $result = mysqli_query($con, $check);
    $row = mysqli_fetch_assoc($result);
    $old_photo = $row['photo'];

    // Check if new image uploaded
    if($_FILES['photo']['name'] != "") {
        $photo_name = $_FILES['photo']['name'];
        $temp_name = $_FILES['photo']['tmp_name'];

        // Move new image
        move_uploaded_file($temp_name, "../../uploads/".$photo_name);

        // Optional: delete old image
        if(file_exists("../../uploads/".$old_photo)) {
            unlink("../../uploads/".$old_photo);
        }

        $query = "UPDATE actors SET 
                    first_name='$first_name', 
                    last_name='$last_name', 
                    birth_date='$birth_date', 
                    photo='$photo_name'
                  WHERE actor_id='$actor_id'";
    } else {
        // Update without changing photo
        $query = "UPDATE actors SET 
                    first_name='$first_name', 
                    last_name='$last_name', 
                    birth_date='$birth_date'
                  WHERE actor_id='$actor_id'";
    }

    mysqli_query($con, $query);
    header("Location: actors.php"); // redirect after update
    exit();
}

// ---------- LOAD ACTOR FOR FORM ----------
if(!isset($_GET['GetID'])) {
    echo "No actor selected!";
    exit();
}

$actor_id = $_GET['GetID'];

$query = "SELECT * FROM actors WHERE actor_id='$actor_id'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

if(!$row) {
    echo "Actor not found!";
    exit();
}

$first_name = $row['first_name'];
$last_name  = $row['last_name'];
$birth_date = $row['birth_date'];
$photo      = $row['photo'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Actor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">

<div class="container">
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card mt-5">
                <div class="card-title">
                    <h3 class="bg-success text-white text-center py-3">
                        Edit Actor
                    </h3>
                </div>
                <div class="card-body">

                    <!-- FORM -->
                    <form action="edit_actor.php?GetID=<?php echo $actor_id ?>" 
                          method="post" 
                          enctype="multipart/form-data">

                        <!-- First Name -->
                        <input type="text" 
                               class="form-control mb-2" 
                               name="name" 
                               value="<?php echo $first_name ?>" 
                               required>

                        <!-- Last Name -->
                        <input type="text" 
                               class="form-control mb-2" 
                               name="last_name" 
                               value="<?php echo $last_name ?>" 
                               required>

                        <!-- Birth Date -->
                        <input type="date" 
                               class="form-control mb-2" 
                               name="birth_date" 
                               value="<?php echo $birth_date ?>" 
                               required>

                        <!-- Current Photo -->
                        <div class="mb-2">
                            <label>Current Photo:</label><br>
                            <?php if(!empty($photo) && file_exists("../../uploads/".$photo)) { ?>
                                <img src="../../uploads/<?php echo $photo ?>" width="120" class="mb-2">
                            <?php } else { ?>
                                <p>No image uploaded</p>
                            <?php } ?>
                        </div>

                        <!-- Upload New Photo -->
                        <input type="file" 
                               class="form-control mb-3" 
                               name="photo">

                        <!-- Submit Button -->
                        <button class="btn btn-primary" name="edit">
                            Update Actor
                        </button>

                        <a href="actors.php" class="btn btn-secondary mt-3 w-100">Back to Actors List</a>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>