<?php
    echo "<nav><a href=\"index.php\" id=\"menu_home\" title=\"Accueil\"></a>
        <a href=\"index.php?page=contact\" id=\"menu_contact\" title=\"Contact\"></a>";

    if (logged_in()){
        echo "<a href=\"index.php?page=profile\" id=\"menu_profil\" title=\"Profil\"></a>";
        if (isModo($db0) || isAdmin($db0))
                echo "<a href=\"index.php?page=modo\" id=\"menu_modo\" title=\"Modération\"></a>";
        if (isAdmin($db0))
            echo "<a href=\"index.php?page=admin\" id=\"menu_admin\" title=\"Administration\"></a>";
        echo "<a href=\"index.php?page=logoff\" id=\"menu_logout\" title=\"Déconnexion\"></a>";
    }
    else{
        echo "<a href=\"index.php?page=register\" id=\"menu_register\" title=\"Enregistrement\"></a>
            <a href=\"index.php?page=login\" id=\"menu_login\" title=\"Connexion\"></a>";
    }
echo "</nav>";
?>