<?php
    require 'connect.php';

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

    $released = filter_input(INPUT_POST, 'released', FILTER_VALIDATE_INT);

    $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(isset($_POST['create'])){

        $insert = "INSERT INTO movies (title, rating, released, genre) VALUES (:title, :rating, :released, :genre)";

        $insertStatement = $db->prepare($insert);
        $insertStatement->bindValue(':title', $title);
        $insertStatement->bindValue(':rating', $rating);
        $insertStatement->bindValue(':released', $released);
        $insertStatement->bindValue(':genre', $genre);

        $insertStatement->execute();

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
</body>
</html>