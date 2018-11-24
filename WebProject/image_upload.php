<?php
    //namespace Gumlet;
    use \Gumlet\ImageResize;
    include 'ImageResize.php';

    $errorFlag = false;

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
    
    if($image_upload_detected){
        $image_filename = $_FILES['image']['name'];
        $temporary_image_path = $_FILES['image']['tmp_name'];
        $new_image_path = file_upload_path($image_filename);

        if(file_is_an_image($temporary_image_path, $new_image_path)){
            move_uploaded_file($temporary_image_path, $new_image_path);

            $image = new ImageResize($new_image_path);
            $image->resizeToWidth(400);
            $image->save('uploads/' . substr($image_filename, 0,  strpos($image_filename, ".")) . '_medium' . substr($image_filename,  strpos($image_filename, "."))  );
        
            $image = new ImageResize($new_image_path);
            $image->resizeToWidth(50);
            $image->save('uploads/' . substr($image_filename, 0,  strpos($image_filename, ".")). '_thumbnail' . substr($image_filename, strpos($image_filename, ".")) );
            

        }
        else if($_FILES['image']['type'] == 'application/pdf' && substr($image_filename,  strpos($image_filename, ".") + 1)  == 'pdf'){
            move_uploaded_file($temporary_image_path, $new_image_path);
        }
    }
    else{
        $errorFlag = true;
    }

  

    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File Upload Challenge</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <label for="image">Image Filename: </label>
        <input type="file" name="image" id="image">
        <input type="submit" name="Submit" value="Upload Image">
    </form>

    

    <?php if ($errorFlag) : ?>
        <?php if( isset($_FILES['image'])  && $_FILES['image']['error'] > 0 ): ?>
            <p>Error Number: <?= $_FILES['image']['error'] ?></p>
            <p>We were unable to upload your file</p>
        <?php endif ?>
    <?php elseif($image_upload_detected) : ?>
        <p>Client-Side Filename: <?= $_FILES['image']['name']?></p>
        <p>Apparent Mime Type: <?= $_FILES['image']['type']?></p>
        <p>Size in Bytes: <?= $_FILES['image']['size']?></p>
        <p>Temporary Path: <?= $_FILES['image']['tmp_name']?></p>
    <?php endif ?>

    <p><?=print_r($_FILES)?></p>
    <p><?= substr($image_filename,  strpos($image_filename, "."))?></p>
</body>
</html>