<?php
    session_start();
    if($_SESSION['admin'] != true){
        $_SESSION['denied'] = true;
        header("location: index.php");
        exit;
    }

    require 'connect.php';
    $errorFlag = false;

    if(isset($_SESSION['emptyEntry'])){
        $errorFlag = true;
    }

    $movieID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM movies WHERE movieID = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(":id", $movieID, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch();

    $gQuery = "SELECT * FROM genres ORDER BY genreID";
    $gStatement = $db->prepare($gQuery);
    $gStatement->execute();
    $genres = $gStatement->fetchAll();

    $image = "SELECT * FROM images WHERE movieID = $movieID";
    $prepImage = $db->prepare($image);
    $prepImage->execute();
    $count = $prepImage->fetch();
?>

     
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Movie Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit-no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
    <script  src="javascript/medium.js"></script>
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
        <h2>Medium Movies - Edit: <span style="color: #00b8e6" ><?=$result['title']?></span> </h2>
            <?php if($errorFlag) : ?>
                <?=$_SESSION['emptyEntry'] ?>
                <?php $_SESSION['emptyEntry'] = null ?>
            <?php endif?>
            <?php if(isset($_SESSION['noGenre'])) : ?>
                <?=$_SESSION['noGenre']?>
                <?php $_SESSION['noGenre'] = null ?>
            <?php endif?>
            <?php if(isset($_SESSION['movieExists'])) : ?>
                <?=$_SESSION['movieExists']?>
                <?php $_SESSION['movieExists'] = null ?>
            <?php endif?>
<form class="form-horizontal" method="post" action="process_post.php">
<fieldset>
<!-- Form Name -->
<legend>Edit the Movie:</legend>
<!-- Text input-->
<div class="form-group">
    <label class="col-md-4 control-label" for="title">Movie Title:</label>  
    <div class="col-md-4">
        <input id="title" name="title" value="<?=$result['title']?>" type="text" placeholder="The Film" class="form-control input-md"> 
    </div>
</div>
<label class="col-md-4 control-label" for="rating">Rate this film:</label>
<div class="col-md-4">
    <select id="rating" name="rating" class="form-control">
    <?php if($result['rating'] == 1) : ?>
        <option selected value="1">one star</option>
    <?php else :?>
        <option value="1">one star</option>
    <?php endif?>
    <?php if($result['rating'] == 2) : ?>
                <option selected value="2">two star</option>
        <?php else :?>
                <option value="2">two star</option>
        <?php endif?>
        <?php if($result['rating'] == 3) : ?>
                <option selected value="3">three star</option>
        <?php else :?>
                <option value="3">three star</option>
        <?php endif?>
        <?php if($result['rating'] == 4) : ?>
                <option selected value="4">four star</option>
        <?php else :?>
                <option value="4">four star</option>
        <?php endif?>
        <?php if($result['rating'] == 5) : ?>
                <option selected value="5">five star</option>
        <?php else :?>
                <option value="5">five star</option>
        <?php endif?>
    </select>
</div>
<!-- Text input-->
<div class="form-group">
    <label class="col-md-4 control-label" for="released">Year:</label>  
    <div class="col-md-4">
        <input id="released" name="released" type="text" value="<?=$result['released']?>" placeholder="2XXX" class="form-control input-md">
    </div>
</div>

<!-- Multiple Checkboxes -->
<div class="form-group">
  <label class="col-md-4 control-label">Available Genres:</label>
  <?php foreach($genres as $genre) : ?>
  <div class="col-md-4">
    <label class="checkbox-inline" for="<?=$genre['genreID']?>">
    <?php if(strpos($result['genre'], $genre['genreName']) !== false ) : ?>
      <input type="checkbox" checked name="genre<?=$genre['genreID']?>" id="<?=$genre['genreID']?>" value="<?=$genre['genreName']?>">
    <?php else: ?>
      <input type="checkbox" name="genre<?=$genre['genreID']?>" id="<?=$genre['genreID']?>" value="<?=$genre['genreName']?>">
    <?php endif?>
      <?=$genre['genreName']?>
    </label>
	</div>
  <?php endforeach?>
</div>
<div class="form-group">
    <input type="hidden" name="id" value="<?=$movieID?>"/>
    <button type="submit" name="update" value="update" class="btn btn-outline-warning">Update Movie</button>
    <button type="submit" name="delete" value="delete" class="btn btn-outline-danger">Delete Movie</button>
</div>
</fieldset>
</form>
</div>
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
</body>
</html>