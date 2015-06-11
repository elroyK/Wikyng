<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        <title><?php echo $ini_array['main_infos']['title']; ?></title>
        <?php
        if (!(logged_in() && !empty($_SESSION['level'])))
            echo '<link rel="stylesheet" type="text/css" href="./style/default.css">';
        else {
            if ($_SESSION['id'] == 17)
                echo '<link rel="stylesheet" type="text/css" href="./style/god.css">';
            else if ($_SESSION['level'] == 2)
                echo '<link rel="stylesheet" type="text/css" href="./style/modo.css">';
            else if ($_SESSION['level'] == 1)
                echo '<link rel="stylesheet" type="text/css" href="./style/admin.css">';
            else echo '<link rel="stylesheet" type="text/css" href="./style/default.css">';
        }
        ?>
    </head>

    <body>
    <div id="topline"></div>
    <a href="index.php"><img src="./img/<?php  if (logged_in() && $_SESSION['id'] == 17) echo 'G_';?>logo.png" alt="Wikyng" id="logo"></a>