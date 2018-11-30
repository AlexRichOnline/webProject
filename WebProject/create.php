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

    require 'connect.php';
    $query = "SELECT * FROM genres ORDER BY genreID";
    $statement = $db->prepare($query);
    $statement->execute();
    $genres = $statement->fetchAll();
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
            <?php endif?>
        </ul>
        <div id="content">
        <h2>Medium Movies - Add a Movie</h2>
            <?php if($errorFlag) : ?>
                <?=$_SESSION['emptyEntry'] ?>
                <?php session_destroy() ?>
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
<legend>Add a Movie:</legend>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="title">Movie Title:</label>  
  <div class="col-md-4">
  <input id="textinput" name="title" id="title" name="title" type="text" placeholder="The Film" class="form-control input-md"> 
  </div>
</div>

<label class="col-md-4 control-label" for="rating">Rate this film:</label>
            <div class="col-md-4">
                <select id="rating" name="rating" class="form-control">
                    <option value="1">one star</option>
                    <option value="2">two star</option>
                    <option value="3">three star</option>
                    <option value="4">four star</option>
                    <option value="5">five star</option>
                </select>
            </div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="released">Year:</label>  
  <div class="col-md-4">
  <input id="released" name="released" type="text" placeholder="2XXX" class="form-control input-md">
  </div>
</div>

<!-- Textarea
<div class="form-group">
  <label class="col-md-4 control-label" for="genre">Genre{s}</label>
  <div class="col-md-4">                     
    <textarea class="form-control" placeholder="Action, Comedy, Spy" id="genre" name="genre"></textarea>
  </div>
</div> -->

<!-- Multiple Checkboxes -->
<div class="form-group">
  <label class="col-md-4 control-label" for="genre">Available Genres:</label>
  <?php foreach($genres as $genre) : ?>
  <div class="col-md-4">
    <label class="checkbox-inline" for="<?=$genre['genreID']?>">
      <input type="checkbox" name="genre<?=$genre['genreID']?>" id="<?=$genre['genreID']?>" value="<?=$genre['genreName']?>">
      <?=$genre['genreName']?>
    </label>
	</div>
  <?php endforeach?>
</div>


<!-- Multiple Checkboxes (inline) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="checkbox-inline">Add a Image:</label>
  <div class="col-md-4">
    <label class="checkbox-inline" for="addImage">
      <input type="checkbox" name="addImage" id="addImage" value="yes">
      Yes
    </label>
  </div>
</div>
</fieldset>
<div class="form-group">
<button type="submit" name="create" value="create" class="btn btn-outline-success">Add Movie</button>
</div
</form>

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