<?php
    if (!logged_in()) fourohfour();
    if(empty($_GET['modify'])) $_GET['modify'] = 0;
    if(empty($_GET['updateAvatar'])) $_GET['updateAvatar'] = 0;
?>
<div id="profilepage">
    <form name="profile" class="customForm smallForm" id="profileForm" method="post">
        <?php if ($_GET['modify']==1) echo'<fieldset><legend>Entrez votre mot de passe pour valider les modifications.</legend>'; ?>
        <label for="uLogin">Nom d'utilisateur :</label>
        <input type="text" name="uLogin" size="20" maxlength="20" required value="<?php echo($_SESSION['login']);?>"
            <?php
                if ($_GET['modify'] == 0) echo(" disabled><br>");
            else echo("><br>");
            ?>
        <label for="uMail">Adresse mail :</label>
        <input type="email" required="email" name="uMail" size="20" maxlength="50" value="<?php echo($_SESSION['mail']);?>"
            <?php
            if ($_GET['modify'] == 0)
                echo 'disabled><br><input type="submit" name="modProf" id="sendButton" value="Modifier le profil">';
            else echo '><br><label for="vMail">Confirmer l\'adresse mail :</label>
                <input type="email" required="email" name="vMail" size="20" maxlength="50" value="'.$_SESSION['mail'].'">
                <br><label for="uPass">Mot de passe :</label><input type="password" required name="uPass" size="20"><br>
                </fieldset><input type="submit" name="applyMod" id="sendButton" value="Appliquer les modifications">';

            if (isset($_POST['modProf'])) {
                echo "<script>window.location.href=\"./index.php?page=profile&modify=1\"</script>";
            }
            if (isset($_POST['applyMod'])){

                $didModif = false;

                if (empty($_POST['uPass'])) enterPass();
                else if(empty($_POST['uLogin']) || empty($_POST['uMail'])
                    || empty($_POST['vMail']) || empty($_POST['vMail'])) unfilledFields();

                if ($_POST['uLogin'] != $_SESSION['login']) {
                    $username = $_POST['uLogin'];
                    $username = safe($username);
                    $usernameLC = strtolower($username);
                    $query1 = $db0->prepare("SELECT userLogin FROM tbuser WHERE userLogin=:login");
                    $query1->bindValue(":login", $usernameLC, PDO::PARAM_STR);
                    $query1->execute();
                    $result1 = $query1->fetch();
                    $items_found1 = (!empty($result1));
                    if($items_found1>0)
                    {
                        $loginTaken = TRUE;
                    }
                    else $loginTaken = FALSE;
                    if(strlen($_POST['uLogin']) < $ini_array['sizes']['login_min']
                        || strlen($_POST['uLogin']) > $ini_array['sizes']['login_max']
                        || !preg_match("/^[a-zA-Z0-9]*$/",$_POST['uLogin'])){
                        wrongULogin();
                    }
                    else if($loginTaken){
                        uLoginTaken();
                    }
                    else {
                        $query2 = $db0->prepare("SELECT userPwd FROM tbuser WHERE userId=:uID");
                        $query2->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                        $query2->execute();
                        $password = $query2->fetch();
                        if ($pass == $password[0]) {
                            $stmt1 = $db0->prepare("UPDATE tbuser SET userLogin = :uLogin WHERE userId=:uID");
                            $stmt1->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                            $_SESSION['login'] = $username;
                            $stmt1->bindValue(':uLogin', $_SESSION['login'], PDO::PARAM_STR);
                            $stmt1->execute();
                            $didModif = true;
                        } else wrongPass();
                    }
                }
                if ($_POST['uMail']!= $_SESSION['mail']) {

                    $tryMail = strtolower($_POST['uMail']);
                    $tryMail = safe($tryMail);
                    $query2 = $db0->prepare("SELECT userMail FROM tbuser WHERE userMail=:tryMail;");
                    $query2->bindValue(':tryMail', $tryMail, PDO::PARAM_STR);
                    $query2->execute();
                    $result2 = $query2->fetch();
                    $items_found2 = (!empty($result2));
                    if($items_found2>0)
                    {
                        $mailTaken = TRUE;
                    }
                    if($mailTaken)
                        uMailTaken();
                    else if (!filter_var($_POST['uMail'], FILTER_VALIDATE_EMAIL)){
                        wrongEmail();
                    }
                    else if ($_POST['uMail'] != $_POST['vMail']){
                        wrongVMail();
                    }
                    else {
                        $query2 = $db0->prepare("SELECT userPwd FROM tbuser WHERE userId=:uID");
                        $query2->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                        $query2->execute();
                        $password = $query2->fetch();
                        if ($pass == $password[0]) {
                            $stmt2 = $db0->prepare("UPDATE tbuser SET userMail = :uMail WHERE userId=:uID");
                            $stmt2->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                            $_SESSION['mail'] = $tryMail;
                            $stmt2->bindValue(':uMail', $_SESSION['mail'], PDO::PARAM_STR);
                            $stmt2->execute();
                            $didModif = true;
                        } else wrongPass();
                    }
                }
                if ($didModif) {
                    session_write_close();
                    modifDone();
                }

            }
            ?>
    </form>

    <div id="profileAvatar">
        <?php
            $imgAvatar = "<img src=\"./img/avatars/".getAvatar($db0,$_SESSION['id'])."\" alt=\"Avatar\" id=\"proAvatar\">";
            if ($_GET['updateAvatar'] == 0)
                echo "<h2>Avatar</h2>".$imgAvatar."
                    <form name=\"avatar\" class=\"customForm\" method=\"post\">
                    <input type=\"submit\" value=\"Modifier l'avatar\" name=\"modifyAvatar\"></form>";
            if (isset($_POST['modifyAvatar']))
            echo "<script>window.location.href=\"./index.php?page=profile&updateAvatar=1\"</script>";
        if ($_GET['updateAvatar'] == 1)
            echo "<h2>Avatar</h2>".$imgAvatar."
                <form enctype=\"multipart/form-data\" method=\"post\" action=\"index.php?page=uploadAvatar\">
                <label for=\"fileToUpload\">Fichier</label><br>
                <input type=\"file\" required name=\"avatarFile\" accept=\"image/*\"><br>
                <input type=\"submit\" value=\"Upload\"></form>";
        ?>
    </div>
</div>
