<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 11/27/15
 * Time: 3:12 PM
 */

    // Get src.
    $src = $_POST["src"];

//    $nsrc = str_replace("/uploads/uploads/","/uploads/",$src);

    // Check if file exists.
    if (file_exists(getcwd() . $src)) {
        // Delete file.
        unlink(getcwd() . $src);
    }
?>