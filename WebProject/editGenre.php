<?php
    session_start();
    if($_SESSION['admin'] != true){
        $_SESSION['denied'] = true;
        header("location: index.php");
        exit;
    }

    $errorFlag = false;
    if(isset($_SESSION['emptyEntry'])){
        $errorFlag = true;
    }



    require "connect.php";
    $genreID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM genres WHERE genreID = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(":id", $genreID, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Genre Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit-no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
    <div id="container">
        <header id="top">
            <h1>Medium Movie Reviews</h1>
        </header>
        <?php if(isset($_SESSION['username'])) :?>
                <h4><span style="color: #00b8e6"><?=$_SESSION['username']?></span></h4>
        <?php endif?>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">About Us</a>
            </li>
            <?php if(isset($_SESSION['loggedOn'])) : ?>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="search.php">Browse Movies</a>
            </li>
            <?php endif?>
            <?php if(isset($_SESSION['admin'])) :?>
            <li class="nav-item">
                <a class="nav-link" href="create.php">Add a Movie</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Genre.php">Add Genre</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="newuser.php">Create User</a>
            </li>
            <?php endif?>
            <?php if(!isset($_SESSION['loggedOn'])) : ?>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.php">Register Account</a>
            </li>
            <?php else :?>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
            <?php endif?>
        </ul>
        <div id="content">
        <h2>Medium Movies - Edit Genre: <span style="color:#00b8e6" ><?=$result['genreName']?></span></h2>
        <?php if(isset($_SESSION['emptyEntry'])) :?>
                <p><?= $_SESSION['emptyEntry']?></p>
                <?php $_SESSION['emptyEntry'] = null ?>
            <?php endif?>
        <form method="post" action="process_genre.php" class="form-horizontal">
            <fieldset>
                <!-- Text input-->
                <label class="col-md-4 control-label" for="genreName">Genre Name:</label> 
                <div class="form-group">
                    <div class="col-md-4">
                        
                        <input id="genreName" name="genreName" value="<?=$result['genreName']?>" type="text" placeholder="Film-Noir" class="form-control input-md">
                    </div>
                </div>

                <!-- Button -->
                <div class="form-group">
                    <div class="col-md-4">
                        <input type="hidden" name="genreID" value="<?=$genreID?>"/>
                        <button type="submit" id="editGenre" name="editGenre" value="edit" class="btn btn-success">Update Genre</button>
                        <button type="submit" id="deleteGenre" name="deleteGenre" value="delete" class="btn btn-danger">Delete Genre</button>
                    </div>
                </div>
            </fieldset>
            </form>
        </div>
        <div id="botNav">
            <footer>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <?php if(isset($_SESSION['loggedOn'])) : ?>
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="search.php">Browse Movies</a></li>
                        <?php endif?>
                        <?php if(isset($_SESSION['admin'])) : ?>
                        <li><a href="create.php">Add a Movie</a></li>
                        <li><a href="Genre.php">Add a Genre</a></li>
                        <li><a href="newuser.php">Create User</a></li>
                        <?php endif ?>
                    </ul>
                </nav>
            </footer>
        </div>
    </div>
</body>
</html>