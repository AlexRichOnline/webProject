<?php
    require 'connect.php';
    $errorFlag = false;
    $uniqueUser = false;
    session_start();

    if(isset($_POST['join']) ){
       
        if(isset($_POST['username'])){
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        $query = "SELECT * FROM account WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();

        if($statement->rowCount() > 0){
            $_SESSION['uniqueUser'] = "Sorry but that username is already in use please select another";
            $uniqueUser = false;
        }
        else{
            $uniqueUser = true;
        }


        if(isset($_POST['pass1'])){
            $pass1 = filter_input(INPUT_POST, 'pass1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        if(isset($_POST['pass2'])){
            $pass2 = filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        
        if($uniqueUser){
            if(!empty($username) && !empty($pass1) && !empty($pass2) && $pass1 == $pass2){

                
            
                $admin = false;

                $insert = "INSERT INTO account (username, password, admin) VALUES (:username, :pass, :admin)";
                $insertStatement = $db->prepare($insert);
                $insertStatement->bindValue(':username', $username);
                $insertStatement->bindValue(':pass', password_hash($pass1, PASSWORD_DEFAULT));
                $insertStatement->bindValue(':admin', $admin);

                $insertStatement->execute();
                $_SESSION['regSuccess'] = "You have successfully registered the account " . $username;
                $_SESSION['email'] = $username;
                header("location: login.php");
                exit;
            }
            else{
                $errorFlag = true;
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit-no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
    <script  src="javascript/medium.js"></script>
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
            <h2>Medium Movies - Register an Account</h2>
            <form action="register.php" method="post">  
            <?php if($errorFlag) :?>
                <legend>Error: Passwords do not match</legend>  
            <?php endif?>
            <?php if(isset($_SESSION['uniqueUser'])) :?>
                <p><?=$_SESSION['uniqueUser']?></p>
                <?php $_SESSION['uniqueUser'] = null?>
            <?php endif ?>
                <div class="form-group">
                    <label for="email1">Enter a Email address to Register</label>
                    <input type="email" name="username" class="form-control" id="email1" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="pass1">Enter Password</label>
                    <input type="password" name="pass1" class="form-control" id="pass1" placeholder="Password">
                    <label for="pass2">Re-Enter</label>
                    <input type="password" name="pass2" class="form-control" id="pass2" placeholder="Password">
                </div>
                <button type="submit" name="join" class="btn btn-primary">Submit</button>
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