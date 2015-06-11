<?php
    function displayUser($array){
        echo "<tr><td>" . $array['userId'] . "</td><td>" . $array['userLogin'] . "</td>
                    <td>" . $array['userMail'] . "</td><td>" . $array['userDateInscription'] . "</td>
                    <td>" . $array['userLastConnected'] . "</td><td><a href=\"index.php?page=profileAdmin&id="
            . $array['userId'] . "\">Modifier</a></td></tr>";
    }

    function displayMessage($array){
        $mesTexte = substr($array['mesTexte'], 0, 44);
        if (strcmp($mesTexte, $array['mesTexte'])!=0) $mesTexte = $mesTexte."(...)";
        echo "<tr><td>" . $array['mesId'] . "</td><td>" . $array['mesSujet'] . "</td>
                    <td>" . $array['mesEmail'] . "</td><td>".$array['userId']."</td><td>" .$mesTexte. "</td>
                    <td>" . $array['mesDate'] . "</td><td><a href=\"index.php?page=contact&mID="
            . $array['mesId'] . "\">Afficher</a></td><td>";
        if ($array['answered'] == 0) echo"<a href=\"index.php?page=contact&uID="
            . $array['userId'] . "&pID=". $array['mesId'] . "\">Répondre</a>";
        echo"</td></tr>";
    }
?>