<?php 
require_once("../connection.php");

// ---------- UPDATE USER ----------
if(isset($_POST['update'])) {

    if(!isset($_GET['user_id'])) {
        echo "No user selected!";
        exit();
    }

    $user_id = intval($_GET['user_id']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $role = mysqli_real_escape_string($con, $_POST['role']);
    $new_password = $_POST['password'];

    // If password field is NOT empty → hash new password
    if(!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update = "UPDATE users 
                   SET username='$username',
                       email='$email',
                       password='$hashed_password',
                       role='$role'
                   WHERE user_id='$user_id'";
    } else {
        // If password empty → don't change it
        $update = "UPDATE users 
                   SET username='$username',
                       email='$email',
                       role='$role'
                   WHERE user_id='$user_id'";
    }

    if(mysqli_query($con, $update)) {
        header("Location: users.php");
        exit();
    } else {
        echo "Update failed: " . mysqli_error($con);
        exit();
    }
}

// ---------- LOAD USER ----------
if(!isset($_GET['user_id'])) {
    echo "No user selected!";
    exit();
}

$user_id = intval($_GET['user_id']);
$query = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($con, $query);

if(!$row = mysqli_fetch_assoc($result)) {
    echo "User not found!";
    exit();
}

$username = $row['username'];
$email = $row['email'];
$role = $row['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<div class="container">
<div class="row">
<div class="col-lg-6 m-auto">
<div class="card mt-5">
<div class="card-title">
<h3 class="bg-success text-white text-center py-3">Edit User</h3>
</div>
<div class="card-body">

<form action="edit_users.php?user_id=<?php echo $user_id ?>" method="post">

<!-- Username -->
<input type="text"
       class="form-control mb-2"
       name="username"
       value="<?php echo htmlspecialchars($username); ?>"
       required>

<!-- Email -->
<input type="email"
       class="form-control mb-2"
       name="email"
       value="<?php echo htmlspecialchars($email); ?>"
       required>

<!-- Password (optional) -->
<input type="password"
       class="form-control mb-2"
       name="password"
       placeholder="Leave empty to keep current password">

<!-- Role -->
<select class="form-control mb-3" name="role" required>
    <option value="user" <?php if($role=='user') echo 'selected'; ?>>User</option>
    <option value="admin" <?php if($role=='admin') echo 'selected'; ?>>Admin</option>
</select>

<button class="btn btn-primary w-100" name="update">Update User</button>

</form>

<a href="users.php" class="btn btn-secondary mt-3 w-100">Back</a>

</div>
</div>
</div>
</div>
</div>

</body>
</html>