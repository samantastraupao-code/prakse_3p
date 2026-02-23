<?php


    require_once("connection.php");


    if(isset($_POST['submit']))
    {
        if(empty($_POST['name']))
        {
            echo ' Please Fill in the Blanks ';
        }
        else
        {
            $name = $_POST['name'];



$query = " insert into records (name) values('$name')";
            $result = mysqli_query($con,$query);


            if($result)
            {
                header("location:admin/main");
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
