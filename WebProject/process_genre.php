<?php 
    session_start();
    if($_SESSION['admin'] != true){
        $_SESSION['denied'] = true;
        header("location: index.php");
        exit;
    }

    require 'connect.php';
    $_SESSION['emptyEntry'] = null;
        /*
         * Tests weather the inputs from the previous pages were left blank
         * no database proccess' are run and the user is redirected back to the previous page with an error message displayed within the form.
         * @param $page The page the user is being redirected to or the previous page
        */
        function redirect($page){
            if(empty($_POST['genreName'])){
                $_SESSION['emptyEntry'] = "We were unable to complete your request ensure the name of the Genre is not empty";
                header("location: $page");
                exit;
            }
        }
     
    $genreID = filter_input(INPUT_POST, 'genreID', FILTER_SANITIZE_NUMBER_INT);  
    $genreName = filter_input(INPUT_POST, 'genreName', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 

    if(isset($_POST['addGenre'])){

        redirect("Genre.php");

        

        $insert = "INSERT INTO genres(genreName) VALUES(:genreName)";
        $insertGenre = $db->prepare($insert);
        $insertGenre->bindValue(':genreName', $genreName);
        $insertGenre->execute();

        header("location: Genre.php");
        exit;
    }
    else if(isset($_POST['deleteGenre'])){
        $delete = "DELETE FROM genres WHERE genreID = :genreID";
        $deleteStatement = $db->prepare($delete);
        $deleteStatement->bindValue(':genreID', $genreID, PDO::PARAM_INT);
        $deleteStatement->execute();

        header("location: Genre.php");
        exit;
    }
    else if(isset($_POST['editGenre'])){
        redirect("editGenre.php?id=" . $genreID);

        $update = "UPDATE genres SET genreName = :genreName WHERE genreID = :genreID";
        $updateStatement = $db->prepare($update);
        $updateStatement->bindValue(':genreName', $genreName);
        $updateStatement->bindValue(':genreID', $genreID);

        $updateStatement->execute();

        header("location: Genre.php");
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