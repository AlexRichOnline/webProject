<?php
    session_start();
    if(!isset($_SESSION['loggedOn'])){
        header("location: login.php");
        exit;
    }

    require 'connect.php';
    $orderBy = "movieID";
    // $allRecords = false;

    // $ascention = "DESC";
    // $sortBy = null;

    // $select = "SELECT * FROM movies ORDER BY $orderBy $ascention";
    // $statement = $db->prepare($select);
    // $statement->execute();
    // $returnSet = $statement->fetchAll();

    $query = "SELECT * FROM genres";
    $prepQuery = $db->prepare($query);
    $prepQuery->execute();
    $genres = $prepQuery->fetchAll();


      
    $orderBy = "movieID";
    $ascention = "DESC";
    $sortBy = null;

    if(isset($_POST['sort'])){
        if($_POST['sort'] == "year" ){
            $orderBy = "released";
            $ascention = "ASC";
            $sortBy = "Oldest to Newest by Year";
        }
        else if($_POST['sort'] == "name"){
            $orderBy = "title";
            $ascention = "ASC";
            $sortBy = "Alphabetically Arranged by Title";
        } 
        else if($_POST['sort'] == "time"){
            $orderBy = "movieID";
            $ascention = "DESC";
            $sortBy = "Reverse Chronological Order of Movies that were added to the system";
        }
    }

    $like = null;

    if(isset($_POST)){
        $like = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $order = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
        $movies = "SELECT * FROM movies ORDER BY $orderBy $ascention";
        $prepMovies = $db->prepare($movies);
        $prepMovies->execute();
        $results = $prepMovies->fetchAll();
    //}
    
    

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
    <div class="container-fluid">
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
        <h2>Medium Movies - Lookup Movies</h2>
        <?php if(isset($like)): ?>
        <h3>Browse: <span style="color: gold"><?=$like?></span></h3>
        <?php else : ?>
        <h3>Browse: Category</h3>
        <?php endif?>
        
        <form  method="post" action="search.php" class="form-horizontal">
              <!-- Select Basic -->
              <div class="form-group" id ="search">
            <label class="col-md-4 control-label" for="selectbasic">Pick a Genre: </label>
            <div class="col-md-5">
            <select id="selectbasic" name="genre" class="form-control">
            <?php foreach($genres as $genre) : ?>
                <option name="<?=$genre['genreName']?>" value="<?=$genre['genreName']?>"><?=$genre['genreName']?></option>
            <?php endforeach?>
            <div class="dropdown-divider"></div>
                <option value="All">Show All</option>
            </select>
            </div>
            <div class="col-md-5">
        <select name = "sort">
                <option value="" disaled>Sort by:</option>
                <option value ="name">By Title</option>
                <option value ="year">By Year</option>
                <option value ="time">By Order Added</option>
            </select>
            <button id="search" name="search" type="submit" class="btn btn-warning">Search</button>
      </div>
      <div>
      
      <h3>Sorted By: <?=$sortBy?></h3>
      </div>
        </form>
        <?php if($like != null) : ?>
        <?php if($like == "All") : ?>
        <ul>
         <?php foreach($results as $result) : ?>
         <li><a href="moviePage.php?id=<?=$result['movieID']?>"><?=$result['title']?></a> - <?=$result['released']?></li>
        <?php endforeach?>
        </ul>
        <?php else : ?>
        <ul>
         <?php foreach($results as $result) : ?>
         <?php if(strpos($result['genre'], $_POST['genre']) !== false ) : ?>
         <li><a href="moviePage.php?id=<?=$result['movieID']?>"><?=$result['title']?></a> - <?=$result['released']?></li>
         <?php endif?>
        <?php endforeach?>
        </ul>
        <?php endif?>
        <?php endif?>
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
</body>
</html>