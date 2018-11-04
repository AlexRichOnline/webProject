<?php
    require 'connect.php';

    $select = "SELECT * FROM movies ORDER BY movieID DESC";
    $statement = $db->prepare($select);
    $statement->execute();
    $movies = $statement->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
    <div id="container">
        <header id="top">
            <h1>Medium Movie Reviews</h1>
        </header>
        <div id="topNav">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="create.php">Add a Movie</a></li>
                </ul>
            </nav>
        </div>
        <section id="content">
            <h4>Welcome</h4>
            <div id="movies">
            <?php for($i = 0; $i < 10; $i++) :?>
                <div class="movie">
                    <h2><?=$movies[$i]['title']?></h2>
                </div>
            <?php endfor?>
            </div>
            <h3>Recent Activities:</h3>
        </section>
        <div id="botNav">
            <footer>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="create.php">Add a Movie</a></li>
                    </ul>
                </nav>
            </footer>
        </div>
    </div>
</body>
</html>