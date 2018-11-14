<?php
    require 'connect.php';

    session_start();
    if($_SESSION['admin'] != true){
        $_SESSION['denied'] = true;
        header("location: index.php");
        exit;
    }
    $_SESSION['emptyEntry'];

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $admin = false;

    $accountID = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if(isset($_POST['admin'])){
        $admin = true;
    }

    function redirect($page){
        if(empty($_POST['username']) || empty($_POST['pass'])){
            $_SESSION['emptyEntry'] = "We were unable to complete your request that the username and password are not empty";
            header("location: $page");
            exit;
        }
    }

    if(isset($_POST['new'])){
        redirect("newuser.php");

        $insert = "INSERT INTO account (username, password, admin) VALUES (:username, :pass, :admin)";

        $insertStatement = $db->prepare($insert);
        $insertStatement->bindValue(':username', $username);
        $insertStatement->bindValue(':pass', $pass);
        $insertStatement->bindValue(':admin', $admin);

        $insertStatement->execute();

        header("location: newuser.php");
        exit;
    }
    else if(isset($_POST['remove'])){
        $delete = "DELETE FROM account WHERE accountID = :id";
        $deleteStatement = $db->prepare($delete);
        $deleteStatement->bindValue(':id', $accountID, PDO::PARAM_INT);
        $deleteStatement->execute();

        header("location: newuser.php");
        exit;
    }
    else if(isset($_POST['change'])){
        redirect("edituser.php?id=" . $_POST['id']);

        $update = "UPDATE account SET username = :username, password = :password, admin = :admin WHERE accountID = :id";
        $updateStatement = $db->prepare($update);
        $updateStatement->bindValue(':username', $username);
        $updateStatement->bindValue(':password', $pass);
        $updateStatement->bindValue(':admin', $admin);
        $updateStatement->bindValue(':id', $accountID, PDO::PARAM_INT);

        $updateStatement->execute();

        header("location: newuser.php");
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
</body>
</html>