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
    <form method="post" action="process_comment.php" class="form-horizontal">
        <!-- Textarea -->
        <div class="form-group">
            <?php if(isset($_SESSION['emptyEntry'])) :?>
                <p><?= $_SESSION['emptyEntry']?></p>
                <?php $_SESSION['emptyEntry'] = null ?>
            <?php endif?>
            <?php if(isset($_SESSION['cantComment'])) :?>
                <p><?=$_SESSION['cantComment']?></p>
                <?php $_SESSION['cantComment'] = null ?>
            <?php endif?>
            <label class="col-md-4 control-label" for="textarea">Comment on the movie:</label>
            <div class="col-md-4">                     
                <textarea class="form-control" placeholder="It was pretty dang medium..." id="textarea" name="textarea"></textarea>
            </div>
        </div>
                <!-- Select Basic -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="selectbasic">Rate this film</label>
            <div class="col-md-4">
                <select id="selectbasic" name="selectbasic" class="form-control">
                    <option value="1">one star</option>
                    <option value="2">two star</option>
                    <option value="3">three star</option>
                    <option value="4">four star</option>
                    <option value="5">five star</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
        <div class="col-sm-10">
            <button type="submit" name="add" class="btn btn-primary">Leave Comment</button>
            <input type="hidden" name="accountID" value="<?=$_SESSION['accountID']?>"/>
            <input type="hidden" name="movieID" value="<?=$movie['movieID']?>"/>
        </div>
    </div>
    </form>
</div>
    <h1><span style="color: #00b8e6">All Comments for <?=$movie['title']?>:</span></h1>
    <?php foreach($comments as $comment) :?>
    <div class="comment">
        <h5>Comment posted on: <?=date('F d, Y  h:i A', strtotime($comment['timestamp']))?></h5>
        <h5><?=$comment['username']?>: "<?=$comment['text']?>"</h5>
        <h5>Rating: <?=$comment['rating']?> stars</h5>
        <?php $accountID = $comment['accountID'] ?>
    <?php 
          $isAdmin = "SELECT * FROM account WHERE accountID = $accountID";
          $adminStatement = $db->prepare($isAdmin);
          $adminStatement->execute();
          $admin = $adminStatement->fetch();
        //   && $admin['admin'] == 0
    ?>
        <?php if(isset($_SESSION['admin']) ) : ?>
        <form method="post" action="process_comment.php">
            <input type="hidden" name="currentFilm" value="<?=$movie['movieID']?>"/>
            <button type="Submit" name="deleteComment" value="<?=$comment['commentID']?>" class="btn btn-outline-danger">Delete</button>
        </form>
        <?php endif?>
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