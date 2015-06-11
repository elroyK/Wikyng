<?php
    session_start();
    $ini_path = "./input/config.ini";
    $ini_array = parse_ini_file($ini_path, true);

    include('input/config.php');
    include('input/functions/alerts.php');
    include('input/functions/back_back.php');
    include('input/functions/front_back.php');
    include('input/functions/front.php');
    include('input/functions/back_front.php');

    try
    {
        $db0 = new PDO("mysql:host=".$db0_host.";port=".$db0_port.";dbname=".$db0_name, $db0_user, $db0_pass);
        $db0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db0->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    catch (PDOException $error_db0)
    {
        die('Error on db0 : '.$error_db0->getMessage());
    }

    if(logged_in()) updateLevel($db0);

    include('input/head.php');
    include('input/menu.php');
    include('input/submenu.php');

    echo "<div id=\"page\">";

    if (empty($_GET['page']))
        $_GET['page'] = 'home';

    if (is_file("input/pages/".$_GET['page'].".php"))
        $page = $_GET['page'];
    else
        $page = "404";

    include("input/pages/".$page.".php");

    echo "</div>";

    include('input/foot.php');
    killDB($db0);
?>