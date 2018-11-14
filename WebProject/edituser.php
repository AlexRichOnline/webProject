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

    $accountID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    require "connect.php";

    $query = "SELECT * FROM account WHERE accountID = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(":id", $accountID, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch();
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
        </ul>
        <div id="content">
            <h4>Edit User</h4>
            <?php if($errorFlag) : ?>
                <?=$_SESSION['emptyEntry'] ?>
                <?php $_SESSION['emptyEntry'] = null?>
            <?php endif?>
        <form method="post" action="proccess_user.php">
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
            <input type="email" name="username" value="<?=$result['username']?>" class="form-control" id="inputEmail3" placeholder="Email">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
            <input type="password" name="pass" value="<?=$result['password']?>" class="form-control" id="inputPassword3" placeholder="Password">
        </div>
    </div>
    </fieldset>
    <div class="form-group row">
        <div class="col-sm-2">Administrative</div>
        <div class="col-sm-10">
        <div class="form-check">
            <?php if($result['admin'] == true):?>
            <input class="form-check-input" checked name="admin" value="true" type="checkbox" id="gridCheck1">
            <label class="form-check-label" for="gridCheck1">
            <?php else : ?>
            <input class="form-check-input" name="admin" value="true" type="checkbox" id="gridCheck1">
            <label class="form-check-label" for="gridCheck1">
            <?php endif?>
            Click to Promote
            </label>
            <input type="hidden" name="id" value="<?=$result['accountID']?>"/>
        </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <button type="submit" name="change" class="btn btn-primary">Update User</button>
            <button type="submit" name="remove" class="btn btn-primary" onclick="return confirm('Are you sure you wish to delete this user?')" />Delete User</button>
        </div>
    </div>
   
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