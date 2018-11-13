<?php
    require 'authenticate.php';
    require 'connect.php';

    session_start();
    $errorFlag = false;

    if(isset($_SESSION['emptyEntry'])){
        $errorFlag = true;
    }

    $movieID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM movies WHERE movieID = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(":id", $movieID, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Page</title>
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
         <form action="process_post.php" method="post">
         <fieldset>
            <legend>Edit Movie</legend>
            <p>
                <?php if($errorFlag) : ?>
                    <?=$_SESSION['emptyEntry']?>
                    <?php session_destroy() ?>
                <?php endif ?>
                <label for="title">Title:</label>
                <input name="title" id="title" value="<?=$result['title']?>" />
                <label for "released">Year:</label>
                <input id="released" name="released" type="text" value="<?=$result['released']?>">
                <label for="rating">Rating out of 5:</label>
                <input name="rating" id="rating" max="5" min="0" type="number" value="<?=$result['rating']?>">
                <label for="series">Series Name: {Optional}</label>
                <input name="series" id="series" type="text" value="<?=$result['seriesName']?>">
            </p>
            <p>
                <label for="genre">Genre{s}:</label>
                <textarea name="genre" id="genre"><?=$result['genre']?></textarea>
            </p>
            <p>
                <input type="hidden" name="id" value="<?=$result['movieID']?>" />
                <input type="submit" name="update" value="Update" />
                <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')" />
            </p>
            </fieldset>
        </form>
            
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