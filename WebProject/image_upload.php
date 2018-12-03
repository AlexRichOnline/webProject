<?php
    
   // use \Gumlet\ImageResize;
    include 'ImageResize.php';
     session_start();

     if($_SESSION['admin'] != true){
         $_SESSION['denied'] = true;
         header("location: index.php");
         exit;
     }

     require 'connect.php';
     
     $movieID = filter_input(INPUT_POST, 'movieID', FILTER_SANITIZE_NUMBER_INT);

     if(isset($_POST['deleteImage'])){
        
        $delete = "DELETE FROM images WHERE movieID = :movieID";
        $deleteStatement = $db->prepare($delete);
        $deleteStatement->bindValue(':movieID', $movieID, PDO::PARAM_INT);
        $deleteStatement->execute();
        unlink("uploads/" . $_POST['deleteImage'] );

        header("location: moviePage.php?id=" . $movieID);
        exit;
     }
     else if(isset($_POST['uploadImage'])){

        $validImage = false;
        function file_upload_path($original_filename, $upload_subfolder_name = 'uploads'){
            $current_folder = dirname(__FILE__);
            
            $path_seqments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
            return join(DIRECTORY_SEPARATOR, $path_seqments);
        }

        function file_is_an_image($temporary_path, $new_path){
            $allowed_mime_types = ['image/gif', 'image/jpeg', 'image/png'];
            $allowed_file_extenstions = ['gif', 'jpeg', 'png'];

            $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
            
            $actual_mime_type = getimagesize($temporary_path)['mime'];

            $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extenstions);
            $mime_type_is_valid = in_array($actual_mime_type, $allowed_mime_types);

            return $file_extension_is_valid && $mime_type_is_valid;
        }

        $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
        $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
        
        if($image_upload_detected){
            $image_filename = $_POST['movieTitle'] . '_' . $_FILES['image']['name'];
            $temporary_image_path = $_FILES['image']['tmp_name'];
            $new_image_path = file_upload_path($image_filename);
            }

        if(file_is_an_image($temporary_image_path, $new_image_path)){
            move_uploaded_file($temporary_image_path, $new_image_path);
            $validImage = true;

            $image = new \Gumlet\ImageResize($new_image_path);
            $image->resizeToWidth(200);
            $image->save('uploads/' .  $image_filename);
            
        }
        else if($_FILES['image']['type'] == 'application/pdf' && substr($image_filename,  strpos($image_filename, ".") + 1)  == 'pdf'){
            move_uploaded_file($temporary_image_path, $new_image_path);
            $validImage = true;

            $image = new \Gumlet\ImageResize($new_image_path);
            $image->resizeToWidth(200);
        }
        if($validImage){

            $insert = "INSERT INTO images (movieID, filename) values (:movieID, :filename)";
            $insertStatement = $db->prepare($insert);
            $insertStatement->bindValue(':movieID', $movieID);
            $insertStatement->bindValue(':filename', $image_filename);
            $insertStatement->execute();

            header("location: index.php");
            exit;
    }
    else{
        $_SESSION['invalidImage'] = "Invalid Image: please ensure your image file is of type png, pdf, gif or jpeg";
        header("location: addImage.php?id=" . $movieID);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Image Upload </title>
</head>
<body>
    <?php if(isset($_POST['uploadImage'])) : ?>
        <?php if ($upload_error_detected) : ?>
            <?php if( isset($_FILES['image'])  && $_FILES['image']['error'] > 0 ): ?>
                <p>Error Number: <?= $_FILES['image']['error'] ?></p>
                <p>We were unable to upload your file</p>
            <?php endif ?>
        <?php elseif($image_upload_detected) : ?>
            <p>Image Upload Successful</p>
            <?=print_r($_POST)?>
        <?php endif ?>
    <?php elseif(isset($_POST['deleteImage'])) :?>   
        <p>Image deleted</p>
    <?php endif?>
</body>
</html>
