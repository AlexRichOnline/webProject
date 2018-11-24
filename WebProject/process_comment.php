<?php
/* Author: Alex Richardson
 * Date: Oct 2, 2018
 * This file is responsible for creating, updating, deleting posts
 * The data within the post is validated and sanitized before being inserted/updated to the database
 * The file handles each task depeneding on which submit field is present within the POST global
*/

        require 'connect.php';

        session_start();

        if($_SESSION['admin'] != true){
            $_SESSION['denied'] = true;
            header("location: index.php");
            exit;
        }

        $_SESSION['emptyEntry'] = null;
        $errorFlag = false;
        $text = filter_input(INPUT_POST, 'textarea', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $rating = filter_input(INPUT_POST, 'selectbasic', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $movieID = filter_input(INPUT_POST, 'movieID', FILTER_SANITIZE_NUMBER_INT);
        $accountID = filter_input(INPUT_POST, 'accountID', FILTER_SANITIZE_NUMBER_INT);

        if(!isset($_SESSION['loggedOn'])){
            $errorFlag = true;
            $_SESSION['cantComment'] = "Sorry about that: you must be logged in to leave a comment";
            header("location: moviePage.php?id=" . $movieID);
            
        }

        /*
         * Tests weather the inputs from the previous pages were left blank
         * no database proccess' are run and the user is redirected back to the previous page with an error message displayed within the form.
         * @param $page The page the user is being redirected to or the previous page
        */
        function redirect($page){
            if(empty($_POST['textarea']) || empty($_POST['selectbasic']) ){
                $_SESSION['emptyEntry'] = "We were unable to complete your request ensure both the rating and your comment are not empty";
                header("location: $page");
                exit;
            }
        }

        

        if(isset($_POST['add'])){    
            
            redirect("moviePage.php?id=" . $movieID);

            if(!$errorFlag){
                $insert = "INSERT INTO comments (text, rating, movieID, accountID, username) values (:text, :rating, :movieID, :accountID, :username)";
                $insertStatement = $db->prepare($insert);
            
                $insertStatement->bindValue(':text', $text);
                $insertStatement->bindValue(':rating', $rating);
                $insertStatement->bindValue(':movieID', $movieID);
                $insertStatement->bindValue(':accountID', $accountID);
                $insertStatement->bindValue(':username', $_SESSION['username']);
                $insertStatement->execute();

                header("location: moviePage.php?id=" . $movieID);
                exit;
            }
        }
        else if(isset($_POST['deleteComment'])){

            $currentMovie = filter_input(INPUT_POST, 'currentFilm', FILTER_SANITIZE_NUMBER_INT);
            $commentID = filter_input(INPUT_POST, 'deleteComment', FILTER_SANITIZE_NUMBER_INT);

            $deleteComment = "DELETE FROM comments WHERE commentID = :commentID";
            $deleteComState = $db->prepare($deleteComment);
            $deleteComState->bindValue(':commentID', $commentID, PDO::PARAM_INT);
            $deleteComState->execute();

            header("location: moviePage.php?id=" . $currentMovie);
            exit;
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Post Process</title>
</head>
<body>
    <?=print_r($_POST)?>
</body>
</html>