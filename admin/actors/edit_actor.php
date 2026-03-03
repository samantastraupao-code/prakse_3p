<?php
require_once("../connection.php");

if(isset($_POST['edit'])) {
    $actor_id = $_GET['GetID'];

    $first_name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];

    // Tagadējais attēls no datubāzes
    $check = "SELECT photo FROM actors WHERE actor_id='$actor_id'";
    $result = mysqli_query($con, $check);
    $row = mysqli_fetch_assoc($result);
    $old_photo = $row['photo'];

    // Pārbauda via jauns attēls ir ievietots
    if($_FILES['photo']['name'] != "") {
        $photo_name = $_FILES['photo']['name'];
        $temp_name = $_FILES['photo']['tmp_name'];

        // Pārvieto jauno attēlu
        move_uploaded_file($temp_name, "../../uploads/".$photo_name);

        // Optional: Dzēš veco attēlu
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
        // Atjauno aktiera datus nemainot attēlu
        $query = "UPDATE actors SET 
                    first_name='$first_name', 
                    last_name='$last_name', 
                    birth_date='$birth_date'
                  WHERE actor_id='$actor_id'";
    }

    mysqli_query($con, $query);
    header("Location: actors.php");
    exit();
}


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

                    <form action="edit_actor.php?GetID=<?php echo $actor_id ?>" 
                          method="post" 
                          enctype="multipart/form-data">

                        <!-- Vārds -->
                        <input type="text" 
                               class="form-control mb-2" 
                               name="name" 
                               value="<?php echo $first_name ?>" 
                               required>

                        <!-- Uzvārds -->
                        <input type="text" 
                               class="form-control mb-2" 
                               name="last_name" 
                               value="<?php echo $last_name ?>" 
                               required>

                        <!-- Dzimšanas dati -->
                        <input type="date" 
                               class="form-control mb-2" 
                               name="birth_date" 
                               value="<?php echo $birth_date ?>" 
                               required>

                        <!-- Tagadējais attēls -->
                        <div class="mb-2">
                            <label>Current Photo:</label><br>
                            <?php if(!empty($photo) && file_exists("../../uploads/".$photo)) { ?>
                                <img src="../../uploads/<?php echo $photo ?>" width="120" class="mb-2">
                            <?php } else { ?>
                                <p>No image uploaded</p>
                            <?php } ?>
                        </div>

                        <!-- Ielādē jauno attēlu -->
                        <input type="file" 
                               class="form-control mb-3" 
                               name="photo">

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