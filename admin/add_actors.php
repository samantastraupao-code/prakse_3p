<?php


    require_once("connection.php");

    if(isset($_POST['submit']))
    {
        if(empty($_POST['first_name']) 
          || empty($_POST['last_name']) 
          || empty($_POST['birth_date'])
          || empty($_POST['photo'])
          )
        {
            echo ' Please Fill in the Blanks ';
        }
        else
        {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $birth_date = $_POST['birth_date'];
            $photo = $_POST['photo'];


$query = " insert into records (first_name, last_name, birth_date, photo) 
values('$first_name', '$last_name', '$birth_date', '$photo')";
            $result = mysqli_query($con,$query);


            if($result)
            {
                header("location: admin/main");
            }
            else
            {
                echo '  Please Check Your Query ';
            }
        }
    }
    else
    {
        header("location: admin/movie.php");
    }
?>
