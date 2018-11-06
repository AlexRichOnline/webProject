<?php
    require 'connect.php';

    $select = "SELECT * FROM movies ORDER BY movieID DESC";
    $statement = $db->prepare($select);
    $statement->execute();
    $movies = $statement->fetchAll();

    $series;
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
            <h1>Welcome</h4>
            <h2>Recent Uploads:</h3>
            <label for="sort">Sort All Movies</label>
            <select name="sort" onchange="header("location: index.php?test")">
                <option value ="ten">10 Movies</option>
                <option value ="all">All Movies</option>
                <option value ="name">By Title</option>
                <option value ="year">By Year</option>
            </select>
            <div id="movies">
            <?php for($i = 0; $i < 10; $i++) :?>
                <div class="movie">
                <?php if(isset($movies[$i]['seriesName'])) : ?>
                        <?php $series = $movies[$i]['seriesName']?>
                    <?php endif ?>
                <?php if (isset($series)) : ?>
                    <h3><a href="moviePage.php?id=<?=$movies[$i]['movieID']?>"><?=$movies[$i]['title']?></a> - <?=$movies[$i]['released']?> - <?=$series?> Series</h3>
                <?php $series = null ?>
                <?php else :?>
                    <h3><a href="moviePage.php?id=<?=$movies[$i]['movieID']?>"><?=$movies[$i]['title']?></a> - <?=$movies[$i]['released']?></h3>
                <?php endif?>
                    <p>
                        <small>
                            <?=$movies[$i]['genre']?>
                            <a href="edit.php?id=<?=$movies[$i]['movieID']?>">Edit Movie</a>
                        </small>
                    </p>
                    <div>
                        <?=$movies[$i]['rating']?>
                    </div>
                </div>
            <?php endfor?>
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