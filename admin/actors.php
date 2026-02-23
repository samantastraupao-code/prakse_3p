<?php 
require_once("connection.php");
$query = "SELECT * FROM actors";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Actors</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<nav>
  <a href="movie.php"> Movie</a> 
  <a href="actors.php"> Actors</a> 
  <a href="genre.php">Genre</a> 
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Aktieri</h1>
    <table class="table table-bordered table-dark">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vārds</th>
                <th>Uzvārds</th>
                <th>Dzimšanas dati</th>
                <th>Attēls</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['actor_id']; ?></td>
                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                <td><?php echo $row['birth_date']; ?></td>
                <td>
                    <img src="../uploads/<?php echo htmlspecialchars($row['photo']); ?>" width="120" alt="Actor image">
                </td>
                <td>
                    <a href="edit_actor.php?GetID=<?php echo $row['actor_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                </td>
                <td>
                    <a href="delete_actor.php?Del=<?php echo $row['actor_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Vai tiešām dzēst šo aktieri?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>