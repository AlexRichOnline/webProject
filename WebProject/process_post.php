<?php
    require 'connect.php';

    session_start();
    $_SESSION['emptyEntry'] = null;
    $errorFlag = false;

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

    $released = filter_input(INPUT_POST, 'released', FILTER_VALIDATE_INT);

    //$genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $movieID = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $unique = "SELECT * FROM movies WHERE title = :title";
    $prepUnique = $db->prepare($unique);
    $prepUnique->bindValue(':title', $title);
    $prepUnique->execute();
    $movieCount = $prepUnique->rowCount();

    if($_POST['create']){
        if($movieCount > 0){
            $_SESSION['movieExists'] = "Sorry that movie title already exists in the system";
            header("location: create.php");
            exit;
        }
   }

    $series = null;
    if(isset($_POST['series']) && !empty($_POST['series'])){
        $series = filter_input(INPUT_POST, 'series', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    
    
    //|| empty($_POST['genre'])
    function redirect($page){
        if(empty($_POST['title']) || empty($_POST['released']) || empty($_POST['rating'])){
            $_SESSION['emptyEntry'] = "We were unable to complete your request ensure the title, rating, year and genre{s} are not empty";
            header("location: $page");
            exit;
        }
    }

    $genreCount = "SELECT MAX(genreID) AS max_id FROM genres";
    $prepCount = $db->prepare($genreCount);
    $prepCount->execute();
    $row = $prepCount->fetchAll();
    $count = $row[0]['max_id'] + 1;
   
    $firstGenre = false;
    for($i = 1; $i < $count; $i++){
        if(isset($_POST['genre' . $i])){
            if(!$firstGenre){
                $genre = filter_input(INPUT_POST, 'genre' . $i, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $firstGenre = true;
            }
            else{
                $genre = $genre . ", " . filter_input(INPUT_POST, 'genre' . $i, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            } 
        }
    }

    

    if(!isset($genre)){
        $_SESSION['noGenre'] = "You must select at least one of the available genres";
        header("location: create.php");
        exit;
    }

    if(isset($_POST['create'])){
        redirect("create.php");

        $insert = "INSERT INTO movies (title, rating, released, genre, seriesName) VALUES (:title, :rating, :released, :genre, :series)";

        $insertStatement = $db->prepare($insert);
        $insertStatement->bindValue(':title', $title);
        $insertStatement->bindValue(':rating', $rating);
        $insertStatement->bindValue(':released', $released);
        $insertStatement->bindValue(':genre', $genre);
        $insertStatement->bindValue(':series', $series);

        $insertStatement->execute();

        if(isset($_POST['addImage'])) {
            $select = "SELECT * FROM movies WHERE title = :title";
            $getFilm = $db->prepare($select);
            $getFilm->bindValue(":title", $title);
            $getFilm->execute();
            $film = $getFilm->fetch();
            header("location: addImage.php?id=" . $film['movieID']);
            exit;
        }

        header("location: index.php");
        exit;
    }
    else if(isset($_POST['delete'])){
        $delete = "DELETE FROM movies WHERE movieID = :id";
        $deleteStatement = $db->prepare($delete);
        $deleteStatement->bindValue(':id', $movieID, PDO::PARAM_INT);
        $deleteStatement->execute();

        header("location: index.php");
        exit;
    }
    else if(isset($_POST['update'])){
        redirect("edit.php?id=" . $_POST['id']);

        $update = "UPDATE movies SET title = :title, rating = :rating, released = :released, genre = :genre, seriesName = :series WHERE movieID = :id";
        $updateStatement = $db->prepare($update);
        $updateStatement->bindValue(':title', $title);
        $updateStatement->bindValue(':rating', $rating);
        $updateStatement->bindValue(':released', $released);
        $updateStatement->bindValue(':genre', $genre);
        $updateStatement->bindValue(':series', $series);
        $updateStatement->bindValue(':id', $movieID);

        $updateStatement->execute();

        header("location: index.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
   <?=print_r($_POST)?>
   <p>Movie ID: <?=$movieID?></p>
   <p>genre: <?=($genre)?></p>
   <p>count: <?=$row[0]['max_id']?></p>
   <p>row: <?=print_r($row)?></p>
   
</body>
</html>