<?php
    require 'connect.php';
    $correctUser = false;
    $loggedIn = false;

    if($_POST){
   
    //$logName = filter_input(INPUT_POST, 'logName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if(isset($_POST['logName'])){
    $logName = $_POST['logName'];
    }

    $select = "SELECT * FROM account WHERE username = :logName";
    $statement = $db->prepare($select);
    $statement->bindValue(':logName', $logName);
    $statement->execute();
    
    $user = $statement->fetch();
    
    if(isset($_POST['pass'])){
    if($user['username'] == $_POST['logName'] && $user['password'] == $_POST['pass']){
        $correctUser = true;
    }
}

   if($correctUser){
       $loggedIn = true;
   }
}

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
        <div id="topNav">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="create.php">Add a Movie</a></li>
                </ul>
            </nav>
        </div>
        <div id="content">
        
        <?php if(!$correctUser) : ?>
            <form method="post">    
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" name="logName" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="pass" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
             </form>
        <?php else : ?>
        <?php session_start() ?>
        <?php $_SESSION['loggedOn']?>
        <?php header("location: profile.php") ?>
        <?php endif?>
        
        <h2><?=print_r($user)?></h2>
        <h2><?=$correctUser?></h2>
        <h3>Seriously?</h3>
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