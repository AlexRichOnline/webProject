<?php
namespace Gumlet;
/**
 * PHP Exception used in the ImageResize class
 */
session_start();
if($_SESSION['admin'] != true){
    $_SESSION['denied'] = true;
    header("location: index.php");
    exit;
}
class ImageResizeException extends \Exception
{
}