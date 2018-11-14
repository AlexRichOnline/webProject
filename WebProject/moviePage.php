<?php
    require 'connect.php';
    session_start();
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


    $query = "SELECT * FROM movies WHERE movieID = :id";
    $statement = $db->prepare($query);

    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $movie = $statement->fetch();

    $select = "SELECT * FROM comments WHERE movieID = :id ORDER BY timestamp DESC";
    $selectStatement = $db->prepare($select);
    $selectStatement->bindValue(":id", $id, PDO::PARAM_INT);
    $selectStatement->execute();
    $comments = $selectStatement->fetchAll();

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
            <div id="movies">
                <div class="movie">
                    <h1><?=$movie['title']?> - <a href="edit.php?id=<?=$movie['movieID']?>">Edit Movie</a></h1>
                    <h3><?=$movie['title']?> is an  <?=$movie['genre']?> movie that was made in  <?=$movie['released']?>.</h3>
                    <?php $series = $movie['seriesName']?>
                    <?php if (isset($series)) : ?>
                    <?php $series = null ?>
                        <h4><?=$movie['title']?> is a part of the  <?=$movie['seriesName']?> franchise.</h4>
                    <?php endif?>  
                        <h4><?=$movie['title']?>'s current rating: <span style="color:gold"><?=$movie['rating']?></span> out of 5</h4>
                </div>
        <form method="post" action="process_comment.php">
        <div class="form-group row">
        <div class="form-group">
    <label for="exampleFormControlTextarea1">Leave a comment</label>
    <textarea class="form-control" name="text" id="exampleFormControlTextarea1" rows="3"></textarea>
  </div>
        <div class="form-group">
    <label for="exampleFormControlSelect2">Example multiple select</label>
    <select multiple class="form-control" name="rating" id="exampleFormControlSelect2">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
    </select>
    <div class="form-group row">
        <div class="col-sm-10">
        <input type="hidden" name="accountID" value="<?=$_SESSION['accountID']?>"/>
        <input type="hidden" name="id" value="<?=$movie['movieID']?>"/>
            <button type="submit" name="add" class="btn btn-primary">Leave Comment</button>
        </div>
    </div>
    </form>
</div>
            <h1><span style="color: #00b8e6">All Comments for <?=$movie['title']?>:</span></h1>
            <?php foreach($comments as $comment) :?>
            <div class="comment">
                <h5>Comment date: <?=date('F d, Y  h:i A', strtotime($comment['timestamp']))?></h5>
                <h5><?=$comment['text']?></h5>
                <h5>Rating: <?=$comment['rating']?> stars</h5>
            <?php endforeach?>
                </div>
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