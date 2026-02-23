<?php

    require_once("connection.php");

    if(isset($_POST['submit']))
    {
        if(empty($_POST['title']) 
          || empty($_POST['description']) 
          || empty($_POST['release_year'])
          || empty($_POST['duration'])
          || empty($_POST['image'])
          || empty($_POST['actor_id'])
          || empty($_POST['genre_id'])
        )
        {
            echo ' Please Fill in the Blanks ';
        }
        else
        {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $release_year = $_POST['release_year'];
            $duration = $_POST['duration'];
            $image = $_POST['image'];
            $actor_id = $_POST['actor_id'];
            $genre_id = $_POST['genre_id'];


$query = " insert into records (title, description, release_year, duretion, image, actor_id, genre_id) 
values
('$title',
'$description',
'$release_year',
'$duration',
'$image',
'$actor_id',
'$genre_id'
)";
            $result = mysqli_query($con,$query);

            if($result)
            {
                header("location: admin/admin/main");
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
