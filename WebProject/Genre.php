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
    $select = "SELECT * FROM genres";
    $statement = $db->prepare($select);
    $statement->execute();
    $returnSet = $statement->fetchAll();

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
    <div id="container">
        <header id="top">
            <h1>Medium Movie Reviews</h1>
        </header>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="create.php">Add a Movie</a>
            </li>
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
            <?php if(isset($_SESSION['admin'])) :?>
            <li class="nav-item">
                <a class="nav-link" href="newuser.php">Create User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Genre.php">Add Genre</a>
            </li>
            <?php endif?>
        </ul>
        <div id="content">
        <h2>Medium Movies - Add a Genre</h2>
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
                        
                        <input id="genreName" name="genreName" type="text" placeholder="Film-Noir" class="form-control input-md">
                    </div>
                </div>

                <!-- Button -->
                <div class="form-group">
                    <div class="col-md-4">
                        <button type="submit" id="addGenre" name="addGenre" value="add" class="btn btn-success">Add Genre</button>
                    </div>
                </div>
            </fieldset>
            </form>
            <h3>Current Genres in the system</h3>
            <ul id="genres">
                <?php foreach($returnSet as $genre) :?>
                    <li><?=$genre['genreName']?> - <span style="color: gold" ><a href="editGenre.php?id=<?=$genre['genreID']?>">Edit</a></span></li>
                <?php endforeach?>
            </ul>
        </div>
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