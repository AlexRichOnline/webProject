<?php
    require 'connect.php';
    session_start();
    


    $select = "SELECT * FROM movies";
    $statement = $db->prepare($select);
    $statement->execute();
    $returnSet = $statement->fetchAll();

   $results_per_page = 10;

   $number_of_results = $statement->rowCount();

   $number_of_pages = ceil($number_of_results/$results_per_page);

   if(!isset($_GET['page'])){
       $page = 1;
   }
   else{
       $page = $_GET['page'];
   }

   $this_page_first_result = ($page - 1) * $results_per_page;

   $query = "SELECT * FROM movies ORDER BY movieID DESC LIMIT $this_page_first_result,$results_per_page";
   $queryStatement = $db->prepare($query);
   $queryStatement->execute();
   $queryReturn = $queryStatement->fetchAll();
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
    <script src="javascript/medium.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid">
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
        <h2>Medium Movies - <span style="color: #00b8e6">Home</span></h2>
            <?php if(isset($_SESSION['denied'])) :?>
            <p>Access Denied: Requires logged on Administrator</p>
            <?php $_SESSION['denied'] = null ?>
            <?php endif?>
            <h5>Movie Listings:</h5>
            <div class="container">
            <div id="movies">
                <?php foreach($queryReturn as $movie) :?>
                        <div class ="row">
                        
                        <?php if(isset($movie['seriesName'])) : ?>
                                <?php $series = $movie['seriesName']?>
                            <?php endif ?>
                        <?php if (isset($series)) : ?>
                            <h3><a href="moviePage.php?id=<?=$movie['movieID']?>"><?=$movie['title']?></a> - <?=$movie['released']?> - <?=$series?> Series</h3>
                        <?php $series = null ?>
                        <?php else :?>
                            <h3><a href="moviePage.php?id=<?=$movie['movieID']?>"><?=$movie['title']?></a> - <?=$movie['released']?></h3>
                        <?php endif?>
                            <p>
                                <small>
                                    <?=$movie['genre']?>
                                    <a href="edit.php?id=<?=$movie['movieID']?>">Edit Movie</a>
                                </small>
                            </p>
                            <p>Rating: <?=$movie['rating']?> out of 5</p>
                        </div>
                    <?php endforeach?>
            
            </div>
            </div>
            <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item disabled">
        </li>
        <?php if(isset($_GET['page'])) : ?>
        <?php $lastPage = $_GET['page'] - 1 ?>
        <?php if($lastPage == 0) : ?>
            <?php $lastPage = 1?>
        <?php endif?>
        <a class="page-link" href="index.php?page=<?=$lastPage?>"><<</a>
        <?php endif?>
        <?php for($page = 1; $page <= $number_of_pages; $page++) : ?>
        <li class="page-item"><a class="page-link" href="index.php?page=<?=$page?>" ><?=$page?></a></li>
        <?php endfor?>
        <?php if(isset($_GET['page'])) : ?>
        <?php $nextPage = $_GET['page'] + 1 ?>
        <?php if($nextPage > $number_of_pages) : ?>
            <?php $nextPage = $number_of_pages?>
        <?php endif?>
        <a class="page-link" href="index.php?page=<?=$nextPage?>">>></a>
        <?php endif?>
    </ul>
    </nav>
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