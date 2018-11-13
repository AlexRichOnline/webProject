<?php
    require 'connect.php';

    $orderBy = "movieID";
    $allRecords = false;

    $ascention = "DESC";
    $sortBy = null;

    if(isset($_GET['sort'])){
        if($_GET['sort'] == "year" ){
            $orderBy = "released";
            $ascention = "ASC";
            $sortBy = "Oldest to Newest by Year";
        }
        else if($_GET['sort'] == "name"){
            $orderBy = "title";
            $ascention = "ASC";
            $sortBy = "Alphabetically Arranged by Title";
        } 
        else if($_GET['sort'] == "time"){
            $orderBy = "movieID";
            $ascention = "DESC";
            $sortBy = "Reverse Chronological Order of Movies that were added to the system";
        }
    }

    $select = "SELECT * FROM movies ORDER BY $orderBy $ascention";
    $statement = $db->prepare($select);
    $statement->execute();
    $movies = $statement->fetchAll();

    $counter = 10;
    function setCounter(){
        $counter = 100;
    }

   

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit-no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="javascript/medium.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
    <div id="container-fluid">
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
            <h2>Movie Listings:</h2>
            <select id="sort">
                <option value="" disaled>Sort by:</option>
                <option value ="name">By Title</option>
                <option value ="year">By Year</option>
                <option value ="time">By Order Added</option>
            </select>
            <h3><?=$sortBy?></h3>
            <button id="all" value="show" type="button">Display All Movies</button>
            <div id="movies">
            <?php if(!$allRecords) : ?>
                <?php for($i = 0; $i < $counter; $i++) :?>
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
            <?php else :?>
                <?php foreach($movies as $movie) :?>
                        <div class="movie">
                        <?php if(isset($movie['seriesName'])) : ?>
                                <?php $series = $movie['seriesName']?>
                            <?php endif ?>
                        <?php if (isset($series)) : ?>
                            <h3><a href="moviePage.php?id=<?=$movie['movieID']?>"><?=$movie['title']?></a> - <?=$movie['released']?> - <?=$series?> Series</h3>
                        <?php $series = null ?>
                        <?php else :?>
                            <h3><a href="moviePage.php?id=<?=$movie['movieID']?>"><?=$movie['title']?></a> - <?=$movie['released']?></h3>
                        <?php endif?>
                            <p>
                                <small>
                                    <?=$movie['genre']?>
                                    <a href="edit.php?id=<?=$movie['movieID']?>">Edit Movie</a>
                                </small>
                            </p>
                            <div>
                                <?=$movie['rating']?>
                            </div>
                        </div>
                    <?php endforeach?>
            <?php endif?>
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