<?php
    require 'authenticate.php';
    session_start();
    $errorFlag = false;
    if(isset($_SESSION['emptyEntry'])){
        $errorFlag = true;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Category Page</title>
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
            <h4>Create a Category</h4>
            <?php if($errorFlag) : ?>
                <?=$_SESSION['emptyEntry'] ?>
                <?php session_destroy() ?>
            <?php endif?>
            <form action="process_post.php" method="post">
                    <label for="title" >Title</label>
                    <input name="title" id="title" type="text">
                    <label for="rating">Rating out of 5</label>
                    <input name="rating" id="rating" max="5" min="0" type="number">
                    <label for "released">Year</label>
                    <input id="released" name="released" type="text">
                    <label for="genre">Genre{s}</label>
                    <input name="genre" id="genre" type="textarea">
                    <label for="series">Series Name: {Optional}</label>
                    <input name="series" id="series" type="text">
                    <input type="submit" name="create" value="create">
            </form>
            <?=print_r($_POST)?>
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