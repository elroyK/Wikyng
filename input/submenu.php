<?php
    if(!empty($_GET['page'])) {
        $pagesInNeed = array('profile', 'modifypassword', 'admin');
        if (in_array($_GET['page'], $pagesInNeed)) {
            $page = $_GET['page'];
            echo '<div id="submenu">';
            if ($page == 'profile'){
                sub_bullet($db0);
                echo "<a href=\"index.php?page=modifypassword\"><h4>Modifier le mot de passe</h4></a>";
            } else if ($page == 'modifypassword'){
                sub_bullet($db0);
                echo "<a href=\"index.php?page=profile\"><h4>Modifier le profil</h4></a>";
            } else if ($page == 'admin'){
                sub_bullet($db0);
                echo "<a href=\"index.php?page=admin&request=user\"><h4>Liste des utilisateurs</h4></a><br>";
                sub_bullet($db0);
                echo "<a href=\"index.php?page=admin&request=messages\"><h4>Liste des messages</h4></a><br>";
                sub_bullet($db0);
                echo "<a href=\"index.php?page=admin&request=config\"><h4>Modifier la configuration</h4></a><br>";
            }
            echo'</div>';
        }
    }
?>