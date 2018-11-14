<?php
    require 'connect.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


    $query = "SELECT * FROM movies WHERE movieID = :id";
    $statement = $db->prepare($query);

    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $movie = $statement->fetch();

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
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="create.php">Add a Movie</a>
            </li>
        </ul>
        <section id="content">
            <div id="movies">
                <div class="movie">
                    <h1><?=$movie['title']?> - <a href="edit.php?id=<?=$movie['movieID']?>">Edit Movie</a></h1>
                    <h2><?=$movie['title']?> is an  <?=$movie['genre']?> movie that was made in  <?=$movie['released']?>.</h2>
                    <?php $series = $movie['seriesName']?>
                    <?php if (isset($series)) : ?>
                    <?php $series = null ?>
                        <h2><?=$movie['title']?> is a part of the  <?=$movie['seriesName']?> franchise.</h2>
                    <?php endif?>  
                        <h2><?=$movie['title']?>'s current rating: <?=$movie['rating']?> out of 5</h2>
                        <h3>Check out <?=$movie['title']?></h3>
                </div>
            <div>
                <form>
                    <legend>Leave a comment on <?=$movie['title']?></legend>
                    <input type="textarea">
                    <input type="submit">
                </form>
            </div>
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