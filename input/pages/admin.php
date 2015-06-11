<?php
    $null = NULL;
    if (!isAdmin($db0)) fourohfour();
    if (!empty($_GET['request'])) {
        $showUsers = false;
        $showUsersOnly = false;
        $showModosOnly = false;
        $showAdminOnly = false;
        $showWFVOnly = false;
        $showWFROnly = false;
        $showWFPOnly = false;
        $showFrozenOnly = false;
        $showUnregOnly = false;
        $showBannedOnly = false;

        $showMessages = false;

        $table4messages = $_GET['request']=="messages";

        $table4user = $_GET['request']=='user' || $_GET['request']=='userOnly' || $_GET['request']=='modoOnly'
            || $_GET['request']=='adminOnly' || $_GET['request']=='wfvOnly' || $_GET['request']=='wfrOnly'
            || $_GET['request']=='wfpOnly' || $_GET['request']=='frozenOnly' || $_GET['request']=='unregOnly'
            || $_GET['request']=='bannedOnly';
        if ($table4user) {
            $adminTable = "<table id=\"adminTable\">
                <thead><th>User ID</th><th>Nom d'utilisateur</th><th>Email</th><th>Date d'inscription</th>
                <th>Dernière connexion</th><th></th></thead>";
            if (empty($_GET['mail']) && empty($_GET['login'])) $query = $db0->prepare("SELECT * from tbuser");
            else if (!empty($_GET['mail'])) {
                $mail = safe($_GET['mail']);
                $query = $db0->prepare("SELECT * from tbuser WHERE userMail=:mail");
                $query->bindValue(':mail', $mail, PDO::PARAM_STR);
            } else if (!empty($_GET['login'])) {
                $login = safe($_GET['login']);
                $query = $db0->prepare("SELECT * from tbuser WHERE userLogin=:login");
                $query->bindValue(':login', $login
                    , PDO::PARAM_STR);
            }
            $query->execute();
            if ($_GET['request']=='user') $showUsers = true;
            if ($_GET['request']=='userOnly') $showUsersOnly = true;
            if ($_GET['request']=='modoOnly') $showModosOnly = true;
            if ($_GET['request']=='adminOnly') $showAdminOnly = true;
            if ($_GET['request']=='wfvOnly') $showWFVOnly = true;
            if ($_GET['request']=='wfrOnly') $showWFROnly = true;
            if ($_GET['request']=='wfpOnly') $showWFPOnly = true;
            if ($_GET['request']=='frozenOnly') $showFrozenOnly = true;
            if ($_GET['request']=='unregOnly') $showUnregOnly = true;
            if ($_GET['request']=='bannedOnly') $showBannedOnly = true;

            echo "<form name=\"filtrer\" method=\"post\"><fieldset><legend>Filtrer :</legend>
                <select name=\"selectArg\">
                    <option value=\"".$null."\">Statut</option>
                    <option value=\"".$ini_array['other']['profilid_admin']."\">Administrateurs</option>
                    <option value=\"".$ini_array['other']['profilid_modo']."\">Modérateurs</option>
                    <option value=\"".$ini_array['other']['profilid_user']."\">Utilisateurs</option>
                    <option value=\"".$ini_array['other']['profilid_WFV']."\">En attente de validation</option>
                    <option value=\"".$ini_array['other']['profilid_WFR']."\">En attente de réactivation</option>
                    <option value=\"".$ini_array['other']['profilid_WFP']."\">En attente de mot de passe</option>
                    <option value=\"".$ini_array['other']['profilid_frozen']."\">Gelés</option>
                    <option value=\"".$ini_array['other']['profilid_deleted']."\">Supprimés</option>
                    <option value=\"".$ini_array['other']['profilid_banned']."\">Bannis</option>
                </select>
                <input type=\"submit\" name=\"filter\" value=\"Filtrer\">
                </fieldset>
            </form>
            <form name=\"chercher\" method=\"post\"><fieldset><legend>Rechercher :</legend>
                <input type=\"search\" name=\"searchArg\" required value=\"Nom d'utilisateur ou email\" onfocus=\"this.value=''; this.onfocus=null;\">
                <input type=\"submit\" name=\"search\" value=\"Rechercher\">
                </fieldset>
            </form>";

            echo($adminTable);

            if ($showUsers) {

                while ($userEntry = $query->fetch()) {
                    displayUser($userEntry);
                }
                echo "</table>";
            } else if ($showUsersOnly) {

                while ($userEntry = $query->fetch()) {
                    if (getUser($db0, $userEntry['userId'])) {
                        displayUser($userEntry);
                    }
                }
                echo "</table>";
            } else if ($showModosOnly) {

                while ($userEntry = $query->fetch()) {
                    if (getModo($db0, $userEntry['userId'])) {
                        displayUser($userEntry);
                    }
                }
                echo "</table>";
            } else if ($showAdminOnly) {

                while ($userEntry = $query->fetch()) {
                    if (getAdmin($db0, $userEntry['userId'])) {
                        displayUser($userEntry);
                    }
                }
                echo "</table>";
            } else if ($showWFVOnly) {

                while ($userEntry = $query->fetch()) {
                    if (getWFV($db0, $userEntry['userId'])) {
                        displayUser($userEntry);
                    }
                }
                echo "</table>";
            } else if ($showWFROnly) {

                while ($userEntry = $query->fetch()) {
                    if (getWFR($db0, $userEntry['userId'])) {
                        displayUser($userEntry);
                    }
                }

            } else if ($showWFPOnly) {

                while ($userEntry = $query->fetch()) {
                    if (getWFP($db0, $userEntry['userId'])) {
                        displayUser($userEntry);
                    }
                }

            } else if ($showFrozenOnly) {

                while ($userEntry = $query->fetch()) {
                    if (getFrozen($db0, $userEntry['userId'])) {
                        displayUser($userEntry);
                    }
                }

            } else if ($showUnregOnly) {

                while ($userEntry = $query->fetch()) {
                    if (getUnreg($db0, $userEntry['userId'])) {
                        displayUser($userEntry);
                    }
                }

            } else if ($showBannedOnly) {

                while ($userEntry = $query->fetch()) {
                    if (getBanned($db0, $userEntry['userId'])) {
                        displayUser($userEntry);
                    }
                }

            }

            if (isset($_POST['filter'])){
                if (empty($_POST['selectArg'])) echo "<script>window.location.href=\"./index.php?page=admin&request=user\"</script>";
                echo "<script>window.location.href=\"./index.php?page=admin&request=";
                switch ($_POST['selectArg']){
                    case $ini_array['other']['profilid_admin'] : echo "adminOnly\"</script>"; break;
                    case $ini_array['other']['profilid_modo'] : echo "modoOnly\"</script>"; break;
                    case $ini_array['other']['profilid_user'] : echo "userOnly\"</script>"; break;
                    case $ini_array['other']['profilid_WFV'] : echo "wfvOnly\"</script>"; break;
                    case $ini_array['other']['profilid_WFR'] : echo "wfrOnly\"</script>"; break;
                    case $ini_array['other']['profilid_WFP'] : echo "wfpOnly\"</script>"; break;
                    case $ini_array['other']['profilid_frozen'] : echo "frozenOnly\"</script>"; break;
                    case $ini_array['other']['profilid_deleted'] : echo "unregOnly\"</script>"; break;
                    case $ini_array['other']['profilid_banned'] : echo "bannedOnly\"</script>"; break;
                }
            }

            if (isset($_POST['search'])){
                if (empty($_POST['searchArg'])) echo "<script>window.location.href=\"./index.php?page=admin&request=user\"</script>";

                $searchArg = safe($_POST['searchArg']);

                if (filter_var($searchArg, FILTER_VALIDATE_EMAIL))
                    echo "<script>window.location.href=\"./index.php?page=admin&request=user&mail=".$searchArg."\"</script>";
                else
                    echo "<script>window.location.href=\"./index.php?page=admin&request=user&login=".$searchArg."\"</script>";
            }
        }

        if ($table4messages){
            echo "<form name=\"filtrer\" method=\"post\"><fieldset><legend>Filtrer :</legend>
                <select name=\"selectArg\">
                    <option value=\"".$null."\"> </option>
                    <option value=\"1\">Récents</option>
                    <option value=\"2\">Non-répondus</option>
                    <option value=\"3\">Anonymes</option>
                    <option value=\"4\">Utilisateurs enregistrés</option>
                </select>
                <input type=\"submit\" name=\"filter\" value=\"Filtrer\">
                </fieldset>
            </form>";

            $adminTable = "<table id=\"adminTable\">
                <thead><th>Message ID</th><th>Sujet</th><th>Email</th><th>User ID</th><th>Contenu (aperçu)</th>
                <th>Date du message</th><th></th><th></th></thead>";

            echo($adminTable);

            if (empty($_GET['filter'])) $query = $db0->prepare("SELECT * from tbmessages");
            else if($_GET['filter'] == "mostRecent") $query = $db0->prepare("SELECT * from tbmessages order by mesDate DESC");
            else if($_GET['filter'] == "unanswered") $query = $db0->prepare("SELECT * from tbmessages where answered = 0");
            else if($_GET['filter'] == "anonymous") $query = $db0->prepare("SELECT * from tbmessages where userId = 0");
            else if($_GET['filter'] == "byUser") $query = $db0->prepare("SELECT * from tbmessages where userId > 0");

            $query->execute();
            while ($messageEntry = $query->fetch()) {
                displayMessage($messageEntry);
            }
            echo "</table>";

            if (isset($_POST['filter'])){
                if (empty($_POST['selectArg'])) echo "<script>window.location.href=\"./index.php?page=admin&request=messages\"</script>";
                echo "<script>window.location.href=\"./index.php?page=admin&request=messages&filter=";
                switch ($_POST['selectArg']){
                    case 1 : echo "mostRecent\"</script>"; break;
                    case 2 : echo "unanswered\"</script>"; break;
                    case 3 : echo "anonymous\"</script>"; break;
                    case 4 : echo "byUser\"</script>"; break;
                }
            }
        }
    }
?>