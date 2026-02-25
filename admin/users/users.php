<?php 


    require_once("../connection.php");
    $query = " select * from users ";
    $result = mysqli_query($con,$query);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" a href="CSS/bootstrap.css"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>View users</title>
</head>
<body class="bg-dark">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
    <a class="navbar-brand" href="../dashboard.php">Admin Panel</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href=""></a></li>
            <li class="nav-item"><a class="nav-link " href="../movie/movie.php">Movies</a></li>
            <li class="nav-item"><a class="nav-link" href="../actors/actors.php">Actors</a></li>
            <li class="nav-item"><a class="nav-link" href="../genre/genre.php">Genres</a></li>
            <li class="nav-item">
    <a class="nav-link active" href="users.php">Users</a>
</li>
        </ul>
        <a href="../../logout.php" class="btn btn-secondary">Logout</a>
    </div>
</nav>


        <div class="container">
            <div class="row">
                <div class="col m-auto">
                    <div class="card mt-5">
                        <table class="table table-bordered">
                            <tr>
                                <td> User ID </td>
                                <td> User Name </td>
                                <td> User Email </td>
                                <td> password </td>
                                <td> role </td>
                                <td> Edit  </td>
                                <td> Delete </td>
                            </tr>


                            <?php 
                                    
                                    while($row=mysqli_fetch_assoc($result))
                                    {
                                 $user_id = $row['user_id'];
                                 $username = $row['username'];
                                 $email = $row['email'];
                                 $password = $row['password'];
                                 $role = $row['role'];
                            ?>
                                <tr>
                                  <td><?php echo $user_id ?></td>
                                  <td><?php echo $username ?></td>
                                  <td><?php echo $email ?></td>
                                  <td><?php echo $password ?></td>
                                  <td><?php echo $role ?></td>
                                  <td>
                                  <a href="edit_users.php?user_id=<?php echo $row['user_id']; ?>" 
                                    class="btn btn-warning btn-sm">
                                    Edit
                                  </a>
                                  </td>

                                  <td>
                    <a href="delete_movie.php?Del=<?php echo $id; ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Vai tiešām dzēst šo lietotāju?')">
                       Delete
                    </a>
                    
                </td>
                               </tr>        
                            <?php 
                                    }  
                            ?>                                                                    
                                   
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
